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

// التحقق من وجود معرف المستخدم
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = (int)$_GET['id'];

// جلب بيانات المستخدم
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: users.php");
        exit;
    }
} catch (PDOException $e) {
    $error = 'حدث خطأ أثناء جلب بيانات المستخدم';
    logError($e->getMessage());
}

// معالجة تحديث بيانات المستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = cleanInput($_POST['username']);
    $email = cleanInput($_POST['email']);
    $role = cleanInput($_POST['role']);
    $status = cleanInput($_POST['status']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // التحقق من البيانات
    if (empty($username)) {
        $error = 'يرجى إدخال اسم المستخدم';
    } elseif (empty($email)) {
        $error = 'يرجى إدخال البريد الإلكتروني';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'يرجى إدخال بريد إلكتروني صحيح';
    } else {
        try {
            // التحقق من عدم وجود البريد الإلكتروني مع مستخدم آخر
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user_id]);
            if ($stmt->fetch()) {
                $error = 'البريد الإلكتروني مستخدم بالفعل';
            } else {
                // معالجة الصورة الشخصية
                $avatar = $user['avatar'];
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
                            // حذف الصورة القديمة إذا وجدت
                            if (!empty($user['avatar']) && file_exists('../' . $user['avatar'])) {
                                unlink('../' . $user['avatar']);
                            }
                            $avatar = 'uploads/avatars/' . $fileName;
                        } else {
                            $error = 'حدث خطأ أثناء رفع الصورة';
                        }
                    } else {
                        $error = 'نوع الملف غير مسموح به';
                    }
                }

                if (empty($error)) {
                    // تحديث كلمة المرور إذا تم تغييرها
                    if (!empty($current_password)) {
                        if (empty($new_password)) {
                            $error = 'يرجى إدخال كلمة المرور الجديدة';
                        } elseif (strlen($new_password) < 6) {
                            $error = 'يجب أن تكون كلمة المرور 6 أحرف على الأقل';
                        } elseif ($new_password !== $confirm_password) {
                            $error = 'كلمات المرور غير متطابقة';
                        } elseif (!password_verify($current_password, $user['password'])) {
                            $error = 'كلمة المرور الحالية غير صحيحة';
                        } else {
                            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $stmt = $pdo->prepare("
                                UPDATE users 
                                SET username = ?, email = ?, password = ?, avatar = ?, role = ?, status = ?
                                WHERE id = ?
                            ");
                            $stmt->execute([
                                $username, $email, $hashed_password, $avatar, $role, $status, $user_id
                            ]);
                        }
                    } else {
                        $stmt = $pdo->prepare("
                            UPDATE users 
                            SET username = ?, email = ?, avatar = ?, role = ?, status = ?
                            WHERE id = ?
                        ");
                        $stmt->execute([
                            $username, $email, $avatar, $role, $status, $user_id
                        ]);
                    }

                    if (empty($error)) {
                        $message = 'تم تحديث بيانات المستخدم بنجاح';
                        logError("تم تحديث بيانات المستخدم: " . $username);
                        
                        // تحديث البيانات المعروضة
                        $user['username'] = $username;
                        $user['email'] = $email;
                        $user['avatar'] = $avatar;
                        $user['role'] = $role;
                        $user['status'] = $status;
                    }
                }
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء تحديث بيانات المستخدم';
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
    <title>تعديل المستخدم - <?php echo SITE_NAME; ?></title>
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

        .current-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .password-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .password-section h3 {
            margin-bottom: 20px;
            color: #333;
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
                <h2>تعديل المستخدم</h2>
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
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني *</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="avatar">الصورة الشخصية</label>
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="../<?php echo htmlspecialchars($user['avatar']); ?>" alt="الصورة الحالية" class="current-avatar">
                        <?php endif; ?>
                        <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="role">الصلاحية</label>
                        <select id="role" name="role" class="form-control">
                            <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>مستخدم</option>
                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>مدير</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">الحالة</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>نشط</option>
                            <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>غير نشط</option>
                        </select>
                    </div>

                    <div class="password-section">
                        <h3>تغيير كلمة المرور</h3>
                        <div class="form-group">
                            <label for="current_password">كلمة المرور الحالية</label>
                            <input type="password" id="current_password" name="current_password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="new_password">كلمة المرور الجديدة</label>
                            <input type="password" id="new_password" name="new_password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        <a href="users.php" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 