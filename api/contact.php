<?php
require_once '../includes/config.php';

// التحقق من CSRF Token
if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في التحقق من الأمان'
    ]);
    exit;
}

// التحقق من البيانات المطلوبة
$required_fields = ['name', 'email', 'phone', 'subject', 'message'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode([
            'success' => false,
            'message' => 'جميع الحقول مطلوبة'
        ]);
        exit;
    }
}

// تنظيف وتأمين البيانات
$name = sanitize_input($_POST['name']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$phone = sanitize_input($_POST['phone']);
$subject = sanitize_input($_POST['subject']);
$message = sanitize_input($_POST['message']);

// التحقق من صحة البريد الإلكتروني
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'البريد الإلكتروني غير صالح'
    ]);
    exit;
}

try {
    // إدخال البيانات في قاعدة البيانات
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([$name, $email, $phone, $subject, $message]);
    
    // إرسال بريد إلكتروني للإدارة
    $to = SITE_EMAIL;
    $email_subject = "رسالة جديدة من $name - $subject";
    $email_body = "
        <h2>رسالة جديدة من نموذج الاتصال</h2>
        <p><strong>الاسم:</strong> $name</p>
        <p><strong>البريد الإلكتروني:</strong> $email</p>
        <p><strong>رقم الهاتف:</strong> $phone</p>
        <p><strong>الموضوع:</strong> $subject</p>
        <p><strong>الرسالة:</strong></p>
        <p>$message</p>
    ";
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . SITE_EMAIL,
        'Reply-To: ' . $email
    ];
    
    mail($to, $email_subject, $email_body, implode("\r\n", $headers));
    
    // إرسال رد تلقائي للعميل
    $client_subject = "شكراً لتواصلكم مع " . SITE_NAME;
    $client_body = "
        <h2>مرحباً $name،</h2>
        <p>شكراً لتواصلكم معنا. لقد استلمنا رسالتكم وسيقوم فريقنا بالرد عليكم في أقرب وقت ممكن.</p>
        <p>تفاصيل رسالتكم:</p>
        <p><strong>الموضوع:</strong> $subject</p>
        <p><strong>الرسالة:</strong> $message</p>
        <hr>
        <p>مع تحيات،<br>" . SITE_NAME . "</p>
    ";
    
    mail($email, $client_subject, $client_body, implode("\r\n", $headers));
    
    echo json_encode([
        'success' => true,
        'message' => 'تم إرسال رسالتك بنجاح'
    ]);
    
} catch (PDOException $e) {
    error_log("Contact Form Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ أثناء معالجة طلبك'
    ]);
} 