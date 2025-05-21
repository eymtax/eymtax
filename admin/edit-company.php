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
$company = null;

// التحقق من وجود معرف الشركة
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: companies.php");
    exit;
}

$company_id = (int)$_GET['id'];

// جلب بيانات الشركة
try {
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([$company_id]);
    $company = $stmt->fetch();

    if (!$company) {
        header("Location: companies.php");
        exit;
    }
} catch (PDOException $e) {
    $error = 'حدث خطأ أثناء جلب بيانات الشركة';
    logError($e->getMessage());
}

// معالجة تحديث بيانات الشركة
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = cleanInput($_POST['name']);
    $description = cleanInput($_POST['description']);
    $address = cleanInput($_POST['address']);
    $phone = cleanInput($_POST['phone']);
    $email = cleanInput($_POST['email']);
    $website = cleanInput($_POST['website']);
    $category = cleanInput($_POST['category']);
    $status = cleanInput($_POST['status']);

    // التحقق من البيانات
    if (empty($name)) {
        $error = 'يرجى إدخال اسم الشركة';
    } elseif (!empty($email) && !isValidEmail($email)) {
        $error = 'البريد الإلكتروني غير صالح';
    } else {
        try {
            // معالجة الصورة
            $logo = $company['logo']; // الاحتفاظ بالشعار القديم
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/companies/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = uniqid() . '.' . $fileExtension;
                    $uploadFile = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadFile)) {
                        // حذف الشعار القديم إذا كان موجوداً
                        if (!empty($company['logo']) && file_exists('../' . $company['logo'])) {
                            unlink('../' . $company['logo']);
                        }
                        $logo = 'uploads/companies/' . $fileName;
                    } else {
                        $error = 'حدث خطأ أثناء رفع الصورة';
                    }
                } else {
                    $error = 'نوع الملف غير مسموح به';
                }
            }

            if (empty($error)) {
                $stmt = $pdo->prepare("
                    UPDATE companies 
                    SET name = ?, description = ?, address = ?, phone = ?, 
                        email = ?, website = ?, logo = ?, category = ?, status = ?
                    WHERE id = ?
                ");

                $stmt->execute([
                    $name, $description, $address, $phone, $email, 
                    $website, $logo, $category, $status, $company_id
                ]);

                $message = 'تم تحديث بيانات الشركة بنجاح';
                logError("تم تحديث بيانات الشركة: " . $name);
                
                // تحديث بيانات الشركة في المتغير
                $company = array_merge($company, [
                    'name' => $name,
                    'description' => $description,
                    'address' => $address,
                    'phone' => $phone,
                    'email' => $email,
                    'website' => $website,
                    'logo' => $logo,
                    'category' => $category,
                    'status' => $status
                ]);
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء تحديث بيانات الشركة';
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
    <title>تعديل الشركة - <?php echo SITE_NAME; ?></title>
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

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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

        .current-logo {
            margin-top: 10px;
            max-width: 200px;
            border-radius: 4px;
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
                    <a href="companies.php" class="nav-link active">الشركات</a>
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
                    <a href="settings.php" class="nav-link">الإعدادات</a>
                </li>
            </ul>
            <a href="logout.php" class="logout-link">تسجيل الخروج</a>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>تعديل الشركة</h2>
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
                        <label for="name">اسم الشركة *</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($company['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($company['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="address">العنوان</label>
                        <input type="text" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($company['address']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">الهاتف</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($company['phone']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($company['email']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="website">الموقع الإلكتروني</label>
                        <input type="url" id="website" name="website" class="form-control" value="<?php echo htmlspecialchars($company['website']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="category">التصنيف</label>
                        <input type="text" id="category" name="category" class="form-control" value="<?php echo htmlspecialchars($company['category']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="logo">الشعار</label>
                        <?php if (!empty($company['logo'])): ?>
                            <img src="../<?php echo htmlspecialchars($company['logo']); ?>" alt="شعار الشركة" class="current-logo">
                        <?php endif; ?>
                        <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                        <small>اترك هذا الحقل فارغاً إذا كنت لا تريد تغيير الشعار</small>
                    </div>

                    <div class="form-group">
                        <label for="status">الحالة</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active" <?php echo $company['status'] === 'active' ? 'selected' : ''; ?>>نشط</option>
                            <option value="inactive" <?php echo $company['status'] === 'inactive' ? 'selected' : ''; ?>>غير نشط</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        <a href="companies.php" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 