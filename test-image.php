<?php
// اختبار عرض صورة من مجلد img
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>اختبار عرض صورة</title>
</head>
<body style="text-align:center; margin-top:40px;">
    <h2>اختبار عرض صورة من مجلد img</h2>
    <img src="img/دليل_الشركات.jpg" alt="دليل الشركات" style="max-width:400px; border:2px solid #0077cc;">
    <p>إذا ظهرت الصورة أعلاه، فالمشكلة ليست في السيرفر أو .htaccess</p>
    <p>إذا لم تظهر، المشكلة في رفع الصورة أو اسمها أو الصلاحيات</p>
</body>
</html> 