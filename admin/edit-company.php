<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: companies.php');
    exit;
}
$id = intval($_GET['id']);
$message = '';

// جلب بيانات الشركة الحالية
$stmt = mysqli_prepare($conn, "SELECT * FROM companies WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$company = mysqli_fetch_assoc($result);
if (!$company) {
    header('Location: companies.php');
    exit;
}

// عند حفظ التعديلات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $description = trim($_POST['description']);
    $image_path = $company['image'];
    // إذا تم رفع صورة جديدة
    if (!empty($_FILES['image']['name'])) {
        $target_dir = '../uploads/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image_path = $target_dir . time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $image_path = str_replace('..', '', $image_path);
    }
    $stmt = mysqli_prepare($conn, "UPDATE companies SET name=?, category=?, address=?, phone=?, description=?, image=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'ssssssi', $name, $category, $address, $phone, $description, $image_path, $id);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: companies.php');
        exit;
    } else {
        $message = 'حدث خطأ أثناء تعديل بيانات الشركة!';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات الشركة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {background: #f8f9fa;}
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
            <h2 class="mb-4">تعديل بيانات الشركة</h2>
            <?php if ($message): ?>
                <div class="alert alert-danger text-center"> <?= $message ?> </div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">اسم الشركة</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($company['name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">التصنيف</label>
                        <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($company['category']) ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($company['address']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الهاتف</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($company['phone']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($company['description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">الصورة الحالية</label><br>
                    <?php if($company['image']): ?>
                        <img src="<?= htmlspecialchars($company['image']) ?>" alt="صورة" width="80">
                    <?php else: ?>
                        <span class="text-muted">لا توجد صورة</span>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">تغيير الصورة</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                <a href="companies.php" class="btn btn-secondary">إلغاء</a>
            </form>
        </main>
    </div>
</div>
</body>
</html> 