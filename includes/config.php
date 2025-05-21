<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'eymtax_db');

// Site Configuration
define('SITE_NAME', 'Eymta X');
define('SITE_URL', 'https://eymtax.com');
define('SITE_EMAIL', 'info@eymtax.com');
define('SITE_PHONE', '0938029294');
define('SITE_ADDRESS', 'سوريا');

// Social Media Links
define('FACEBOOK_URL', 'https://facebook.com/eymtax');
define('INSTAGRAM_URL', 'https://instagram.com/eymtax');
define('TWITTER_URL', 'https://twitter.com/eymtax');
define('LINKEDIN_URL', 'https://linkedin.com/company/eymtax');

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Security Configuration
define('HASH_COST', 12);
define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_NAME', 'csrf_token');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Time Zone
date_default_timezone_set('Asia/Damascus');

// Database Connection
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
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Session Configuration
session_start();
session_regenerate_id(true);

// CSRF Protection
if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

// Helper Functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_csrf_token() {
    return $_SESSION[CSRF_TOKEN_NAME];
}

function verify_csrf_token($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        redirect('/login.php');
    }
}

function format_date($date) {
    return date('Y-m-d H:i:s', strtotime($date));
}

function get_file_extension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

function is_valid_file_type($filename) {
    $extension = get_file_extension($filename);
    return in_array($extension, ALLOWED_FILE_TYPES);
}

function generate_unique_filename($filename) {
    $extension = get_file_extension($filename);
    return uniqid() . '_' . time() . '.' . $extension;
}

function upload_file($file, $directory = '') {
    if (!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid file parameters.');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('File upload failed.');
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        throw new RuntimeException('File size exceeds limit.');
    }

    if (!is_valid_file_type($file['name'])) {
        throw new RuntimeException('Invalid file type.');
    }

    $upload_path = UPLOAD_DIR . $directory;
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    $filename = generate_unique_filename($file['name']);
    $filepath = $upload_path . $filename;

    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    return $filename;
}

// Error Handler
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    $error_message = date('Y-m-d H:i:s') . " - Error [$errno]: $errstr in $errfile on line $errline\n";
    error_log($error_message, 3, __DIR__ . '/../logs/error.log');
    
    if (ini_get('display_errors')) {
        echo "<div style='color: red; padding: 10px; margin: 10px; border: 1px solid red;'>";
        echo "<strong>Error:</strong> $errstr<br>";
        echo "<strong>File:</strong> $errfile<br>";
        echo "<strong>Line:</strong> $errline";
        echo "</div>";
    }
    
    return true;
}

set_error_handler('custom_error_handler');

// Exception Handler
function custom_exception_handler($exception) {
    $error_message = date('Y-m-d H:i:s') . " - Exception: " . $exception->getMessage() . 
                    " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
    error_log($error_message, 3, __DIR__ . '/../logs/error.log');
    
    if (ini_get('display_errors')) {
        echo "<div style='color: red; padding: 10px; margin: 10px; border: 1px solid red;'>";
        echo "<strong>Exception:</strong> " . $exception->getMessage() . "<br>";
        echo "<strong>File:</strong> " . $exception->getFile() . "<br>";
        echo "<strong>Line:</strong> " . $exception->getLine();
        echo "</div>";
    }
}

set_exception_handler('custom_exception_handler'); 