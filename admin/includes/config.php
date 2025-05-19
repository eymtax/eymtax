<?php
// إعدادات قاعدة البيانات
$host = 'localhost';
$db   = 'eymtrift_eymtax_db';
$user = 'eymtrift_eymtax_user';
$pass = 'Eymtax@2024#Secure';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('فشل الاتصال بقاعدة البيانات: ' . mysqli_connect_error());
}
// تعيين الترميز
mysqli_set_charset($conn, 'utf8mb4');
?> 