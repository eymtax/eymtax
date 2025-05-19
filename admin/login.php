<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/config.php';
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    // بيانات الدخول الافتراضية
    $admin_user = 'mzkph';
    $admin_pass = '239418';
    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $message = 'اسم المستخدم أو كلمة المرور غير صحيحة';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {background: #f8f9fa;}
        .login-box {max-width: 400px; margin: 80px auto; background: #fff; border-radius: 10px; box-shadow: 0 0 20px #eee; padding: 30px;}
        .form-label {float: right;}
    </style>
</head>
<body>
    <div class="login-box">
        <h3 class="mb-4 text-center">تسجيل الدخول للوحة التحكم</h3>
        <?php if ($message): ?>
            <div class="alert alert-danger text-center"> <?= $message ?> </div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div class="mb-3">
                <label for="username" class="form-label">اسم المستخدم</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">دخول</button>
        </form>
    </div>
</body>
</html> 