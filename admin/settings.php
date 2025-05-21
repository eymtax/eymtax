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

// جلب إعدادات الموقع
try {
    $stmt = $pdo->query("SELECT * FROM settings");
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) {
    $error = 'حدث خطأ أثناء جلب الإعدادات';
    logError($e->getMessage());
}

// معالجة تحديث الإعدادات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // تحديث معلومات الموقع
        $site_name = cleanInput($_POST['site_name']);
        $site_description = cleanInput($_POST['site_description']);
        $site_keywords = cleanInput($_POST['site_keywords']);
        $site_email = cleanInput($_POST['site_email']);
        $site_phone = cleanInput($_POST['site_phone']);
        $site_address = cleanInput($_POST['site_address']);
        $site_facebook = cleanInput($_POST['site_facebook']);
        $site_twitter = cleanInput($_POST['site_twitter']);
        $site_instagram = cleanInput($_POST['site_instagram']);

        // التحقق من البيانات
        if (empty($site_name)) {
            $error = 'يرجى إدخال اسم الموقع';
        } elseif (empty($site_email)) {
            $error = 'يرجى إدخال البريد الإلكتروني';
        } elseif (!filter_var($site_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'يرجى إدخال بريد إلكتروني صحيح';
        } else {
            // معالجة الشعار
            $logo = $settings['site_logo'] ?? '';
            if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = strtolower(pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = 'logo.' . $fileExtension;
                    $uploadFile = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $uploadFile)) {
                        // حذف الشعار القديم إذا وجد
                        if (!empty($settings['site_logo']) && file_exists('../' . $settings['site_logo'])) {
                            unlink('../' . $settings['site_logo']);
                        }
                        $logo = 'uploads/' . $fileName;
                    } else {
                        $error = 'حدث خطأ أثناء رفع الشعار';
                    }
                } else {
                    $error = 'نوع الملف غير مسموح به';
                }
            }

            if (empty($error)) {
                // تحديث الإعدادات في قاعدة البيانات
                $settings_to_update = [
                    'site_name' => $site_name,
                    'site_description' => $site_description,
                    'site_keywords' => $site_keywords,
                    'site_email' => $site_email,
                    'site_phone' => $site_phone,
                    'site_address' => $site_address,
                    'site_facebook' => $site_facebook,
                    'site_twitter' => $site_twitter,
                    'site_instagram' => $site_instagram,
                    'site_logo' => $logo
                ];

                foreach ($settings_to_update as $key => $value) {
                    $stmt = $pdo->prepare("
                        INSERT INTO settings (setting_key, setting_value) 
                        VALUES (?, ?) 
                        ON DUPLICATE KEY UPDATE setting_value = ?
                    ");
                    $stmt->execute([$key, $value, $value]);
                }

                $message = 'تم تحديث الإعدادات بنجاح';
                logError("تم تحديث إعدادات الموقع");

                // تحديث الإعدادات المعروضة
                $settings = array_merge($settings, $settings_to_update);
            }
        }
    } catch (PDOException $e) {
        $error = 'حدث خطأ أثناء تحديث الإعدادات';
        logError($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات الموقع - <?php echo SITE_NAME; ?></title>
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

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section h3 {
            margin-bottom: 20px;
            color: #333;
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

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .current-logo {
            max-width: 200px;
            margin-bottom: 10px;
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
                    <a href="users.php" class="nav-link">المستخدمين</a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link active">الإعدادات</a>
                </li>
            </ul>
            <a href="logout.php" class="logout-link">تسجيل الخروج</a>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>إعدادات الموقع</h2>
            </div>

            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-section">
                        <h3>معلومات الموقع</h3>
                        <div class="form-group">
                            <label for="site_name">اسم الموقع *</label>
                            <input type="text" id="site_name" name="site_name" class="form-control" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="site_description">وصف الموقع</label>
                            <textarea id="site_description" name="site_description" class="form-control"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="site_keywords">الكلمات المفتاحية</label>
                            <input type="text" id="site_keywords" name="site_keywords" class="form-control" value="<?php echo htmlspecialchars($settings['site_keywords'] ?? ''); ?>">
                            <small>افصل بين الكلمات بفاصلة</small>
                        </div>

                        <div class="form-group">
                            <label for="site_logo">شعار الموقع</label>
                            <?php if (!empty($settings['site_logo'])): ?>
                                <img src="../<?php echo htmlspecialchars($settings['site_logo']); ?>" alt="الشعار الحالي" class="current-logo">
                            <?php endif; ?>
                            <input type="file" id="site_logo" name="site_logo" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>معلومات الاتصال</h3>
                        <div class="form-group">
                            <label for="site_email">البريد الإلكتروني *</label>
                            <input type="email" id="site_email" name="site_email" class="form-control" value="<?php echo htmlspecialchars($settings['site_email'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="site_phone">رقم الهاتف</label>
                            <input type="text" id="site_phone" name="site_phone" class="form-control" value="<?php echo htmlspecialchars($settings['site_phone'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="site_address">العنوان</label>
                            <textarea id="site_address" name="site_address" class="form-control"><?php echo htmlspecialchars($settings['site_address'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>روابط التواصل الاجتماعي</h3>
                        <div class="form-group">
                            <label for="site_facebook">فيسبوك</label>
                            <input type="url" id="site_facebook" name="site_facebook" class="form-control" value="<?php echo htmlspecialchars($settings['site_facebook'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="site_twitter">تويتر</label>
                            <input type="url" id="site_twitter" name="site_twitter" class="form-control" value="<?php echo htmlspecialchars($settings['site_twitter'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="site_instagram">انستغرام</label>
                            <input type="url" id="site_instagram" name="site_instagram" class="form-control" value="<?php echo htmlspecialchars($settings['site_instagram'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 