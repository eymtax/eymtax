<?php
require_once "includes/config.php";

// التحقق من تسجيل الدخول مسبقاً
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$message = '';
$error = '';
$valid_token = false;
$user_id = null;

// التحقق من وجود رمز إعادة التعيين
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $error = 'رابط غير صالح';
} else {
    $token = cleanInput($_GET['token']);
    
    try {
        // البحث عن المستخدم باستخدام الرمز
        $stmt = $pdo->prepare("
            SELECT id 
            FROM users 
            WHERE reset_token = ? 
            AND reset_expires > NOW() 
            AND status = 'active'
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $valid_token = true;
            $user_id = $user['id'];
        } else {
            $error = 'رابط غير صالح أو منتهي الصلاحية';
        }
    } catch (PDOException $e) {
        $error = 'حدث خطأ أثناء التحقق من الرابط';
        logError($e->getMessage());
    }
}

// معالجة إعادة تعيين كلمة المرور
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // التحقق من كلمة المرور
    if (empty($password)) {
        $error = 'يرجى إدخال كلمة المرور الجديدة';
    } elseif (strlen($password) < 8) {
        $error = 'يجب أن تكون كلمة المرور 8 أحرف على الأقل';
    } elseif ($password !== $confirm_password) {
        $error = 'كلمات المرور غير متطابقة';
    } else {
        try {
            // تحديث كلمة المرور
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                UPDATE users 
                SET password = ?, 
                    reset_token = NULL, 
                    reset_expires = NULL 
                WHERE id = ?
            ");
            $stmt->execute([$hashed_password, $user_id]);

            if ($stmt->rowCount() > 0) {
                $message = 'تم إعادة تعيين كلمة المرور بنجاح';
                logError("تم إعادة تعيين كلمة المرور للمستخدم رقم: " . $user_id);
                
                // توجيه المستخدم إلى صفحة تسجيل الدخول بعد 3 ثواني
                header("refresh:3;url=login.php");
            } else {
                $error = 'حدث خطأ أثناء تحديث كلمة المرور';
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء تحديث كلمة المرور';
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
    <title>إعادة تعيين كلمة المرور - <?php echo SITE_NAME; ?></title>
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
            <p>إعادة تعيين كلمة المرور</p>
        </div>

        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($valid_token): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="password">كلمة المرور الجديدة</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">تأكيد كلمة المرور</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn">تحديث كلمة المرور</button>
            </form>
        <?php endif; ?>

        <div class="links">
            <a href="login.php">العودة إلى تسجيل الدخول</a>
            <a href="../index.php">العودة إلى الموقع</a>
        </div>
    </div>
</body>
</html> 