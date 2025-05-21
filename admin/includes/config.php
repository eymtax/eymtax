<?php
// إعدادات قاعدة البيانات
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'eymtax_db');

// إعدادات الموقع
define('SITE_URL', 'https://eymtax.com');
define('SITE_NAME', 'Eymta X');
define('SITE_DESC', 'أفضل شركة تسويق في سورية');

// إعدادات الأمان
define('SECURE_SESSION', true);
define('SESSION_LIFETIME', 3600); // ساعة واحدة

// إعدادات البريد الإلكتروني
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');

// إعدادات التخزين المؤقت
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600);

// إعدادات التصحيح
define('DEBUG_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// إنشاء اتصال قاعدة البيانات
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    if(DEBUG_MODE) {
        die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
    } else {
        die("عذراً، حدث خطأ في الاتصال بقاعدة البيانات");
    }
}

// بدء الجلسة
if (SECURE_SESSION) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 1);
}
session_start();

// دالة للتحقق من تسجيل الدخول
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// دالة للتحقق من الصلاحيات
function hasPermission($permission) {
    return isset($_SESSION['permissions']) && in_array($permission, $_SESSION['permissions']);
}

// دالة لتنظيف المدخلات
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// دالة للتحقق من صحة البريد الإلكتروني
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// دالة لتسجيل الأخطاء
function logError($message) {
    $logFile = __DIR__ . '/../logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    error_log($logMessage, 3, $logFile);
}
?> 