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

// التحقق من وجود معرف الخدمة
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: services.php");
    exit;
}

$service_id = (int)$_GET['id'];

// جلب بيانات الخدمة
try {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();

    if (!$service) {
        header("Location: services.php");
        exit;
    }
} catch (PDOException $e) {
    $error = 'حدث خطأ أثناء جلب بيانات الخدمة';
    logError($e->getMessage());
}

// معالجة تحديث الخدمة
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = cleanInput($_POST['name']);
    $description = cleanInput($_POST['description']);
    $category = cleanInput($_POST['category']);
    $price = cleanInput($_POST['price']);
    $status = cleanInput($_POST['status']);

    // التحقق من البيانات
    if (empty($name)) {
        $error = 'يرجى إدخال اسم الخدمة';
    } elseif (empty($price) || !is_numeric($price)) {
        $error = 'يرجى إدخال سعر صحيح للخدمة';
    } else {
        try {
            // معالجة الصورة
            $image = $service['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/services/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = uniqid() . '.' . $fileExtension;
                    $uploadFile = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        // حذف الصورة القديمة إذا كانت موجودة
                        if (!empty($service['image']) && file_exists('../' . $service['image'])) {
                            unlink('../' . $service['image']);
                        }
                        $image = 'uploads/services/' . $fileName;
                    } else {
                        $error = 'حدث خطأ أثناء رفع الصورة';
                    }
                } else {
                    $error = 'نوع الملف غير مسموح به';
                }
            }

            if (empty($error)) {
                $stmt = $pdo->prepare("
                    UPDATE services 
                    SET name = ?, description = ?, category = ?, price = ?, image = ?, status = ?
                    WHERE id = ?
                ");

                $stmt->execute([
                    $name, $description, $category, $price, $image, $status, $service_id
                ]);

                $message = 'تم تحديث الخدمة بنجاح';
                logError("تم تحديث خدمة: " . $name);
                
                // تحديث بيانات الخدمة المعروضة
                $service['name'] = $name;
                $service['description'] = $description;
                $service['category'] = $category;
                $service['price'] = $price;
                $service['image'] = $image;
                $service['status'] = $status;
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء تحديث الخدمة';
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
    <title>تعديل الخدمة - <?php echo SITE_NAME; ?></title>
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

        .current-image {
            margin-top: 10px;
        }

        .current-image img {
            max-width: 200px;
            max-height: 200px;
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
                    <a href="companies.php" class="nav-link">الشركات</a>
                </li>
                <li class="nav-item">
                    <a href="services.php" class="nav-link active">الخدمات</a>
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
                <h2>تعديل الخدمة</h2>
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
                        <label for="name">اسم الخدمة *</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($service['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($service['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="category">التصنيف</label>
                        <input type="text" id="category" name="category" class="form-control" value="<?php echo htmlspecialchars($service['category']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="price">السعر (ريال) *</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" value="<?php echo htmlspecialchars($service['price']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="image">الصورة</label>
                        <?php if (!empty($service['image'])): ?>
                            <div class="current-image">
                                <img src="../<?php echo htmlspecialchars($service['image']); ?>" alt="صورة الخدمة الحالية">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="status">الحالة</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active" <?php echo $service['status'] === 'active' ? 'selected' : ''; ?>>نشط</option>
                            <option value="inactive" <?php echo $service['status'] === 'inactive' ? 'selected' : ''; ?>>غير نشط</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        <a href="services.php" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 