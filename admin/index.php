<?php
session_start();

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - Eymta X</title>
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
        .welcome-message {
            font-size: 1.5rem;
            color: #333;
        }
        .logout-btn {
            padding: 0.5rem 1rem;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .card {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin: 0 0 1rem 0;
            color: #333;
        }
        .card p {
            margin: 0;
            font-size: 2rem;
            color: #0077cc;
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
                <div class="welcome-message">مرحباً، <?php echo htmlspecialchars($_SESSION["username"]); ?></div>
                <a href="logout.php" class="logout-btn">تسجيل الخروج</a>
            </div>
            
            <div class="dashboard-cards">
                <div class="card">
                    <h3>الشركات</h3>
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM companies";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    echo "<p>" . $row['total'] . "</p>";
                    ?>
                </div>
                
                <div class="card">
                    <h3>الصور</h3>
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM images";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    echo "<p>" . $row['total'] . "</p>";
                    ?>
                </div>
                
                <div class="card">
                    <h3>المستخدمين</h3>
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM users";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    echo "<p>" . $row['total'] . "</p>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 