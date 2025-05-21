<?php
require_once "includes/config.php";

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// التحقق من الصلاحيات
if (!hasPermission('admin')) {
    header("Location: ../index.php");
    exit;
}

$message = '';
$error = '';

// معالجة إضافة المستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = cleanInput($_POST['username']);
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = cleanInput($_POST['role']);
    $status = cleanInput($_POST['status']);

    // التحقق من البيانات
    if (empty($username)) {
        $error = 'يرجى إدخال اسم المستخدم';
    } elseif (empty($email)) {
        $error = 'يرجى إدخال البريد الإلكتروني';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'يرجى إدخال بريد إلكتروني صحيح';
    } elseif (empty($password)) {
        $error = 'يرجى إدخال كلمة المرور';
    } elseif (strlen($password) < 6) {
        $error = 'يجب أن تكون كلمة المرور 6 أحرف على الأقل';
    } elseif ($password !== $confirm_password) {
        $error = 'كلمات المرور غير متطابقة';
    } else {
        try {
            // التحقق من عدم وجود البريد الإلكتروني
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'البريد الإلكتروني مستخدم بالفعل';
            } else {
                // معالجة الصورة الشخصية
                $avatar = '';
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../uploads/avatars/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileExtension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                    if (in_array($fileExtension, $allowedExtensions)) {
                        $fileName = uniqid() . '.' . $fileExtension;
                        $uploadFile = $uploadDir . $fileName;

                        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
                            $avatar = 'uploads/avatars/' . $fileName;
                        } else {
                            $error = 'حدث خطأ أثناء رفع الصورة';
                        }
                    } else {
                        $error = 'نوع الملف غير مسموح به';
                    }
                }

                if (empty($error)) {
                    // تشفير كلمة المرور
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("
                        INSERT INTO users (username, email, password, avatar, role, status)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");

                    $stmt->execute([
                        $username, $email, $hashed_password, $avatar, $role, $status
                    ]);

                    $message = 'تم إضافة المستخدم بنجاح';
                    logError("تمت إضافة مستخدم جديد: " . $username);
                }
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء إضافة المستخدم';
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
    <title>إضافة مستخدم جديد - <?php echo SITE_NAME; ?></title>
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
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #111;
            color: white;
            padding: 20px;
        }

        .sidebar-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            color: #ccc;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        .nav-link.active {
            background-color: #0077cc;
            color: white;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .header h2 {
            color: #333;
            margin: 0;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
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

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #0077cc;
            color: white;
        }

        .btn-primary:hover {
            background-color: #005fa3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .logout-link {
            color: #dc3545;
            text-decoration: none;
            display: block;
            padding: 10px;
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="sidebar-header">
                <h1><?php echo SITE_NAME; ?></h1>
                <p>لوحة التحكم</p>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a href="companies.php" class="nav-link">الشركات</a>
                </li>
                <li class="nav-item">
                    <a href="services.php" class="nav-link">الخدمات</a>
                </li>
                <li class="nav-item">
                    <a href="blog.php" class="nav-link">المدونة</a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link active">المستخدمين</a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link">الإعدادات</a>
                </li>
            </ul>
            <a href="logout.php" class="logout-link">تسجيل الخروج</a>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>إضافة مستخدم جديد</h2>
            </div>

            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">اسم المستخدم *</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني *</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">كلمة المرور *</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">تأكيد كلمة المرور *</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="avatar">الصورة الشخصية</label>
                        <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="role">الصلاحية</label>
                        <select id="role" name="role" class="form-control">
                            <option value="user">مستخدم</option>
                            <option value="admin">مدير</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">الحالة</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">إضافة المستخدم</button>
                        <a href="users.php" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 