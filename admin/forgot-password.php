<?php
require_once "includes/config.php";

// التحقق من تسجيل الدخول مسبقاً
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$message = '';
$error = '';

// معالجة طلب استعادة كلمة المرور
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email']);

    // التحقق من البريد الإلكتروني
    if (empty($email)) {
        $error = 'يرجى إدخال البريد الإلكتروني';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'يرجى إدخال بريد إلكتروني صحيح';
    } else {
        try {
            // البحث عن المستخدم
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // إنشاء رمز إعادة التعيين
                $reset_token = bin2hex(random_bytes(32));
                $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // تحديث بيانات المستخدم
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET reset_token = ?, reset_expires = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$reset_token, $reset_expires, $user['id']]);

                // إرسال البريد الإلكتروني
                $reset_link = SITE_URL . '/admin/reset-password.php?token=' . $reset_token;
                $to = $user['email'];
                $subject = 'إعادة تعيين كلمة المرور - ' . SITE_NAME;
                $message = "
                    <html>
                    <head>
                        <title>إعادة تعيين كلمة المرور</title>
                    </head>
                    <body>
                        <h2>مرحباً {$user['username']}</h2>
                        <p>لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بك.</p>
                        <p>يمكنك النقر على الرابط التالي لإعادة تعيين كلمة المرور:</p>
                        <p><a href='{$reset_link}'>{$reset_link}</a></p>
                        <p>ينتهي هذا الرابط خلال ساعة واحدة.</p>
                        <p>إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد الإلكتروني.</p>
                        <p>مع تحيات،<br>" . SITE_NAME . "</p>
                    </body>
                    </html>
                ";

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: ' . SITE_NAME . ' <' . SITE_EMAIL . '>' . "\r\n";

                if (mail($to, $subject, $message, $headers)) {
                    $message = 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني';
                    logError("تم إرسال رابط إعادة تعيين كلمة المرور للمستخدم: " . $user['username']);
                } else {
                    $error = 'حدث خطأ أثناء إرسال البريد الإلكتروني';
                    logError("فشل إرسال رابط إعادة تعيين كلمة المرور للمستخدم: " . $user['username']);
                }
            } else {
                $error = 'البريد الإلكتروني غير مسجل في النظام';
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء معالجة الطلب';
            logError($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعادة كلمة المرور - <?php echo SITE_NAME; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #0077cc;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: #0077cc;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #005fa3;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin-bottom: 10px;
        }

        .links a:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo SITE_NAME; ?></h1>
            <p>استعادة كلمة المرور</p>
        </div>

        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <button type="submit" class="btn">إرسال رابط إعادة التعيين</button>
        </form>

        <div class="links">
            <a href="login.php">العودة إلى تسجيل الدخول</a>
            <a href="../index.php">العودة إلى الموقع</a>
        </div>
    </div>
</body>
</html> 