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

// جلب الإحصائيات
try {
    // عدد الشركات
    $stmt = $pdo->query("SELECT COUNT(*) FROM companies");
    $companies_count = $stmt->fetchColumn();

    // عدد الخدمات
    $stmt = $pdo->query("SELECT COUNT(*) FROM services");
    $services_count = $stmt->fetchColumn();

    // عدد المستخدمين
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $users_count = $stmt->fetchColumn();

    // عدد المقالات
    $stmt = $pdo->query("SELECT COUNT(*) FROM blog_posts");
    $posts_count = $stmt->fetchColumn();

    // آخر الشركات المضافة
    $stmt = $pdo->query("
        SELECT c.*, cat.name as category_name 
        FROM companies c 
        LEFT JOIN categories cat ON c.category_id = cat.id 
        ORDER BY c.created_at DESC 
        LIMIT 5
    ");
    $latest_companies = $stmt->fetchAll();

    // آخر المقالات
    $stmt = $pdo->query("
        SELECT p.*, u.username as author_name 
        FROM blog_posts p 
        LEFT JOIN users u ON p.author_id = u.id 
        ORDER BY p.created_at DESC 
        LIMIT 5
    ");
    $latest_posts = $stmt->fetchAll();

    // آخر المستخدمين المسجلين
    $stmt = $pdo->query("
        SELECT * FROM users 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $latest_users = $stmt->fetchAll();

} catch (PDOException $e) {
    logError($e->getMessage());
    $error = 'حدث خطأ أثناء جلب الإحصائيات';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - <?php echo SITE_NAME; ?></title>
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            color: #666;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: #0077cc;
        }

        .section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .section h3 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .list {
            list-style: none;
        }

        .list-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item img {
            width: 50px;
            height: 50px;
            border-radius: 4px;
            object-fit: cover;
        }

        .list-item-content {
            flex: 1;
        }

        .list-item-title {
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        .list-item-meta {
            font-size: 14px;
            color: #666;
        }

        .list-item-date {
            font-size: 12px;
            color: #999;
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

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
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
                    <a href="dashboard.php" class="nav-link active">الرئيسية</a>
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
                    <a href="settings.php" class="nav-link">الإعدادات</a>
                </li>
            </ul>
            <a href="logout.php" class="logout-link">تسجيل الخروج</a>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>لوحة التحكم</h2>
            </div>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>الشركات</h3>
                    <div class="number"><?php echo $companies_count; ?></div>
                </div>
                <div class="stat-card">
                    <h3>الخدمات</h3>
                    <div class="number"><?php echo $services_count; ?></div>
                </div>
                <div class="stat-card">
                    <h3>المستخدمين</h3>
                    <div class="number"><?php echo $users_count; ?></div>
                </div>
                <div class="stat-card">
                    <h3>المقالات</h3>
                    <div class="number"><?php echo $posts_count; ?></div>
                </div>
            </div>

            <div class="section">
                <h3>آخر الشركات المضافة</h3>
                <ul class="list">
                    <?php foreach ($latest_companies as $company): ?>
                        <li class="list-item">
                            <?php if (!empty($company['logo'])): ?>
                                <img src="../<?php echo htmlspecialchars($company['logo']); ?>" alt="<?php echo htmlspecialchars($company['name']); ?>">
                            <?php endif; ?>
                            <div class="list-item-content">
                                <div class="list-item-title"><?php echo htmlspecialchars($company['name']); ?></div>
                                <div class="list-item-meta"><?php echo htmlspecialchars($company['category_name']); ?></div>
                            </div>
                            <div class="list-item-date"><?php echo date('Y/m/d', strtotime($company['created_at'])); ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="section">
                <h3>آخر المقالات</h3>
                <ul class="list">
                    <?php foreach ($latest_posts as $post): ?>
                        <li class="list-item">
                            <?php if (!empty($post['image'])): ?>
                                <img src="../<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            <?php endif; ?>
                            <div class="list-item-content">
                                <div class="list-item-title"><?php echo htmlspecialchars($post['title']); ?></div>
                                <div class="list-item-meta">بواسطة: <?php echo htmlspecialchars($post['author_name']); ?></div>
                            </div>
                            <div class="list-item-date"><?php echo date('Y/m/d', strtotime($post['created_at'])); ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="section">
                <h3>آخر المستخدمين المسجلين</h3>
                <ul class="list">
                    <?php foreach ($latest_users as $user): ?>
                        <li class="list-item">
                            <?php if (!empty($user['avatar'])): ?>
                                <img src="../<?php echo htmlspecialchars($user['avatar']); ?>" alt="<?php echo htmlspecialchars($user['username']); ?>">
                            <?php endif; ?>
                            <div class="list-item-content">
                                <div class="list-item-title"><?php echo htmlspecialchars($user['username']); ?></div>
                                <div class="list-item-meta"><?php echo htmlspecialchars($user['email']); ?></div>
                            </div>
                            <div class="list-item-date"><?php echo date('Y/m/d', strtotime($user['created_at'])); ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html> 