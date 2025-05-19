<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

// حذف شركة إذا تم طلب ذلك
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM companies WHERE id = $id");
    header('Location: companies.php');
    exit;
}

// جلب جميع الشركات
$result = mysqli_query($conn, "SELECT * FROM companies ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الشركات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {background: #f8f9fa;}
        .table thead th {background: #343a40; color: #fff;}
        .actions {white-space: nowrap;}
        .sidebar {min-height: 100vh; background: #343a40; color: #fff;}
        .sidebar a {color: #fff; text-decoration: none; display: block; padding: 12px 20px;}
        .sidebar a.active, .sidebar a:hover {background: #495057;}
        .content {padding: 30px;}
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky">
                <h4 class="text-center py-4">لوحة التحكم</h4>
                <a href="dashboard.php">الرئيسية</a>
                <a href="companies.php" class="active">إدارة الشركات</a>
                <a href="#">إدارة الصور</a>
                <a href="logout.php">تسجيل الخروج</a>
            </div>
        </nav>
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>إدارة الشركات</h2>
                <a href="add-company.php" class="btn btn-success">إضافة شركة جديدة</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الشركة</th>
                            <th>التصنيف</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>الوصف</th>
                            <th>الصورة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?php if($row['image']): ?><img src="<?= htmlspecialchars($row['image']) ?>" alt="صورة" width="60"><?php endif; ?></td>
                            <td class="actions">
                                <a href="edit-company.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">تعديل</a>
                                <a href="companies.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف الشركة؟');">حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
</body>
</html> 