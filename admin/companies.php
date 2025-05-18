<?php
session_start();

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Process delete operation
if(isset($_GET["delete"]) && !empty(trim($_GET["delete"]))){
    $sql = "DELETE FROM companies WHERE id = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = trim($_GET["delete"]);
        
        if(mysqli_stmt_execute($stmt)){
            header("location: companies.php");
            exit();
        } else{
            echo "عذراً، حدث خطأ ما. الرجاء المحاولة مرة أخرى لاحقاً.";
        }
    }
    
    mysqli_stmt_close($stmt);
}

// Fetch all companies
$sql = "SELECT * FROM companies ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الشركات - لوحة التحكم</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #111;
            color: white;
            padding: 1rem;
        }
        .sidebar h2 {
            margin: 0 0 2rem 0;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .nav-menu li {
            margin-bottom: 0.5rem;
        }
        .nav-menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 0.75rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .nav-menu a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            flex: 1;
            padding: 2rem;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-title {
            font-size: 1.5rem;
            color: #333;
        }
        .add-btn {
            padding: 0.5rem 1rem;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .add-btn:hover {
            background-color: #218838;
        }
        .companies-table {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .companies-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .companies-table th,
        .companies-table td {
            padding: 1rem;
            text-align: right;
            border-bottom: 1px solid #eee;
        }
        .companies-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .action-btn {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            text-decoration: none;
            margin-left: 0.5rem;
            font-size: 0.875rem;
        }
        .edit-btn {
            background-color: #0077cc;
            color: white;
        }
        .edit-btn:hover {
            background-color: #005fa3;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .company-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2>لوحة التحكم</h2>
            <ul class="nav-menu">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="companies.php">إدارة الشركات</a></li>
                <li><a href="images.php">إدارة الصور</a></li>
                <li><a href="users.php">إدارة المستخدمين</a></li>
                <li><a href="content.php">إدارة المحتوى</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1 class="page-title">إدارة الشركات</h1>
                <a href="add-company.php" class="add-btn">إضافة شركة جديدة</a>
            </div>
            
            <div class="companies-table">
                <table>
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>اسم الشركة</th>
                            <th>التصنيف</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                echo "<tr>";
                                echo "<td><img src='../img/companies/" . $row['image'] . "' class='company-image' alt='" . $row['name'] . "'></td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['category'] . "</td>";
                                echo "<td>" . $row['address'] . "</td>";
                                echo "<td>" . $row['phone'] . "</td>";
                                echo "<td>";
                                echo "<a href='edit-company.php?id=" . $row['id'] . "' class='action-btn edit-btn'>تعديل</a>";
                                echo "<a href='companies.php?delete=" . $row['id'] . "' class='action-btn delete-btn' onclick='return confirm(\"هل أنت متأكد من حذف هذه الشركة؟\")'>حذف</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center;'>لا توجد شركات مسجلة</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 