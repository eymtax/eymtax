<?php
require_once "includes/config.php";

// التحقق من تسجيل الدخول مسبقاً
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];

    // التحقق من البيانات
    if (empty($email)) {
        $error = 'يرجى إدخال البريد الإلكتروني';
    } elseif (empty($password)) {
        $error = 'يرجى إدخال كلمة المرور';
    } else {
        try {
            // البحث عن المستخدم
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // تسجيل الدخول
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // تسجيل عملية تسجيل الدخول
                logError("تم تسجيل دخول المستخدم: " . $user['username']);

                // توجيه المستخدم إلى لوحة التحكم
                header("Location: dashboard.php");
                exit;
            } else {
                $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء تسجيل الدخول';
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
    <title>تسجيل الدخول - <?php echo SITE_NAME; ?></title>
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

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
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

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><?php echo SITE_NAME; ?></h1>
            <p>تسجيل الدخول إلى لوحة التحكم</p>
        </div>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn">تسجيل الدخول</button>
        </form>

        <a href="../index.php" class="back-link">العودة إلى الموقع</a>
    </div>
</body>
</html> 