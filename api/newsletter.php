<?php
require_once '../includes/config.php';

// Set response header to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'طريقة طلب غير صحيحة'
    ]);
    exit;
}

// Get and sanitize email
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'البريد الإلكتروني غير صالح'
    ]);
    exit;
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'أنت مشترك بالفعل في النشرة البريدية'
        ]);
        exit;
    }

    // Insert new subscriber
    $stmt = $pdo->prepare("
        INSERT INTO newsletter_subscribers (email, status, created_at, updated_at)
        VALUES (?, 'active', NOW(), NOW())
    ");
    
    $stmt->execute([$email]);

    echo json_encode([
        'success' => true,
        'message' => 'تم الاشتراك في النشرة البريدية بنجاح'
    ]);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ أثناء الاشتراك في النشرة البريدية'
    ]);
} 