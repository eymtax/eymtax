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

// معالجة حذف الخدمة
if (isset($_POST['delete_service'])) {
    $service_id = (int)$_POST['service_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$service_id]);
        $message = 'تم حذف الخدمة بنجاح';
    } catch (PDOException $e) {
        $error = 'حدث خطأ أثناء حذف الخدمة';
        logError($e->getMessage());
    }
}

// جلب قائمة الخدمات
try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY created_at DESC");
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'حدث خطأ أثناء جلب بيانات الخدمات';
    logError($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الخدمات - <?php echo SITE_NAME; ?></title>
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

        .services-table {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .services-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .services-table th,
        .services-table td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }

        .services-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .services-table tr:last-child td {
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

        .service-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
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
                <h2>إدارة الخدمات</h2>
                <a href="add-service.php" class="add-button">إضافة خدمة جديدة</a>
            </div>

            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="services-table">
                <table>
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>التصنيف</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                        <tr>
                            <td>
                                <?php if (!empty($service['image'])): ?>
                                    <img src="../<?php echo htmlspecialchars($service['image']); ?>" alt="صورة الخدمة" class="service-image">
                                <?php else: ?>
                                    <span>لا توجد صورة</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($service['name']); ?></td>
                            <td><?php echo htmlspecialchars($service['category']); ?></td>
                            <td><?php echo htmlspecialchars($service['price']); ?> ريال</td>
                            <td>
                                <span class="status-<?php echo $service['status']; ?>">
                                    <?php echo $service['status'] === 'active' ? 'نشط' : 'غير نشط'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit-service.php?id=<?php echo $service['id']; ?>" class="edit-button">تعديل</a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟');">
                                        <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                        <button type="submit" name="delete_service" class="delete-button">حذف</button>
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