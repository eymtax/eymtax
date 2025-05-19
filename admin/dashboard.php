<?php
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - دليل الشركات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {background: #f8f9fa;}
        .sidebar {min-height: 100vh; background: #343a40; color: #fff;}
        .sidebar a {color: #fff; text-decoration: none; display: block; padding: 12px 20px;}
        .sidebar a.active, .sidebar a:hover {background: #495057;}
        .content {padding: 30px;}
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky">
                <h4 class="text-center py-4">لوحة التحكم</h4>
                <a href="dashboard.php" class="active">الرئيسية</a>
                <a href="#">إدارة الشركات</a>
                <a href="#">إدارة الصور</a>
                <a href="logout.php">تسجيل الخروج</a>
            </div>
        </nav>
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 content">
            <h2>مرحباً بك في لوحة التحكم</h2>
            <p>يمكنك من هنا إدارة الشركات والصور في موقعك بكل سهولة.</p>
        </main>
    </div>
</div>
</body>
</html> 