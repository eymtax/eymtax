<?php
require_once "includes/config.php";

// تسجيل الخروج
session_start();
session_destroy();

// حذف كوكيز الجلسة
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// توجيه المستخدم إلى صفحة تسجيل الدخول
header("Location: login.php");
exit;
?> 