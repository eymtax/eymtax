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

// معالجة إضافة المقال
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = cleanInput($_POST['title']);
    $content = $_POST['content']; // لا نقوم بتنظيف المحتوى لأنه قد يحتوي على HTML
    $status = cleanInput($_POST['status']);
    $author_id = $_SESSION['user_id'];

    // التحقق من البيانات
    if (empty($title)) {
        $error = 'يرجى إدخال عنوان المقال';
    } elseif (empty($content)) {
        $error = 'يرجى إدخال محتوى المقال';
    } else {
        try {
            // معالجة الصورة
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/blog/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = uniqid() . '.' . $fileExtension;
                    $uploadFile = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        $image = 'uploads/blog/' . $fileName;
                    } else {
                        $error = 'حدث خطأ أثناء رفع الصورة';
                    }
                } else {
                    $error = 'نوع الملف غير مسموح به';
                }
            }

            if (empty($error)) {
                $stmt = $pdo->prepare("
                    INSERT INTO blog_posts (title, content, image, author_id, status)
                    VALUES (?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $title, $content, $image, $author_id, $status
                ]);

                $message = 'تم إضافة المقال بنجاح';
                logError("تمت إضافة مقال جديد: " . $title);
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء إضافة المقال';
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
    <title>إضافة مقال جديد - <?php echo SITE_NAME; ?></title>
    <!-- إضافة TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            language: 'ar',
            directionality: 'rtl',
            height: 500
        });
    </script>
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

        /* تخصيص محرر TinyMCE */
        .tox-tinymce {
            border-radius: 4px !important;
            border-color: #ddd !important;
        }

        .tox .tox-toolbar__primary {
            background-color: #f8f9fa !important;
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
                    <a href="blog.php" class="nav-link active">المدونة</a>
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
                <h2>إضافة مقال جديد</h2>
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
                        <label for="title">عنوان المقال *</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="content">محتوى المقال *</label>
                        <textarea id="content" name="content" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">الصورة الرئيسية</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="status">الحالة</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">نشر المقال</button>
                        <a href="blog.php" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 