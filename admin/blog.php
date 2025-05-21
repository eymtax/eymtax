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

// معالجة حذف المقال
if (isset($_POST['delete_post'])) {
    $post_id = (int)$_POST['post_id'];
    try {
        // جلب معلومات المقال قبل الحذف
        $stmt = $pdo->prepare("SELECT image FROM blog_posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch();

        // حذف الصورة إذا كانت موجودة
        if ($post && !empty($post['image']) && file_exists('../' . $post['image'])) {
            unlink('../' . $post['image']);
        }

        // حذف المقال من قاعدة البيانات
        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $message = 'تم حذف المقال بنجاح';
    } catch (PDOException $e) {
        $error = 'حدث خطأ أثناء حذف المقال';
        logError($e->getMessage());
    }
}

// جلب قائمة المقالات
try {
    $stmt = $pdo->query("
        SELECT p.*, u.username as author_name 
        FROM blog_posts p 
        LEFT JOIN users u ON p.author_id = u.id 
        ORDER BY p.created_at DESC
    ");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'حدث خطأ أثناء جلب بيانات المقالات';
    logError($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المدونة - <?php echo SITE_NAME; ?></title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            color: #333;
            margin: 0;
        }

        .add-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .add-button:hover {
            background-color: #218838;
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

        .posts-table {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .posts-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .posts-table th,
        .posts-table td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }

        .posts-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .posts-table tr:last-child td {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .edit-button,
        .delete-button {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .edit-button {
            background-color: #0077cc;
            color: white;
        }

        .edit-button:hover {
            background-color: #005fa3;
        }

        .delete-button {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .status-active {
            color: #28a745;
        }

        .status-inactive {
            color: #dc3545;
        }

        .post-image {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }

        .post-title {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .post-excerpt {
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #666;
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
                <h2>إدارة المدونة</h2>
                <a href="add-post.php" class="add-button">إضافة مقال جديد</a>
            </div>

            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="posts-table">
                <table>
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>المحتوى</th>
                            <th>الكاتب</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td>
                                <?php if (!empty($post['image'])): ?>
                                    <img src="../<?php echo htmlspecialchars($post['image']); ?>" alt="صورة المقال" class="post-image">
                                <?php else: ?>
                                    <span>لا توجد صورة</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="post-title"><?php echo htmlspecialchars($post['title']); ?></div>
                            </td>
                            <td>
                                <div class="post-excerpt"><?php echo htmlspecialchars($post['content']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($post['created_at'])); ?></td>
                            <td>
                                <span class="status-<?php echo $post['status']; ?>">
                                    <?php echo $post['status'] === 'active' ? 'نشط' : 'غير نشط'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="edit-button">تعديل</a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المقال؟');">
                                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                        <button type="submit" name="delete_post" class="delete-button">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 