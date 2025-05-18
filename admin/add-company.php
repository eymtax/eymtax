<?php
session_start();

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

$name = $category = $address = $phone = $description = $image = "";
$name_err = $category_err = $address_err = $phone_err = $image_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "الرجاء إدخال اسم الشركة";
    } else{
        $name = trim($_POST["name"]);
    }
    
    // Validate category
    if(empty(trim($_POST["category"]))){
        $category_err = "الرجاء اختيار تصنيف الشركة";
    } else{
        $category = trim($_POST["category"]);
    }
    
    // Validate address
    if(empty(trim($_POST["address"]))){
        $address_err = "الرجاء إدخال عنوان الشركة";
    } else{
        $address = trim($_POST["address"]);
    }
    
    // Validate phone
    if(empty(trim($_POST["phone"]))){
        $phone_err = "الرجاء إدخال رقم الهاتف";
    } else{
        $phone = trim($_POST["phone"]);
    }
    
    // Validate description
    $description = trim($_POST["description"]);
    
    // Validate image
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];
        
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) {
            $image_err = "الرجاء اختيار صورة بصيغة صحيحة (JPG, JPEG, PNG, GIF)";
        }
        
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) {
            $image_err = "حجم الصورة يجب أن لا يتجاوز 5 ميجابايت";
        }
        
        // Verify MIME type of the file
        if(in_array($filetype, $allowed)){
            // Generate unique filename
            $new_filename = uniqid() . "." . $ext;
            $upload_path = "../img/companies/" . $new_filename;
            
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $upload_path)){
                $image = $new_filename;
            } else{
                $image_err = "حدث خطأ أثناء رفع الصورة";
            }
        } else{
            $image_err = "نوع الملف غير مدعوم";
        }
    } else{
        $image_err = "الرجاء اختيار صورة للشركة";
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($category_err) && empty($address_err) && empty($phone_err) && empty($image_err)){
        $sql = "INSERT INTO companies (name, category, address, phone, description, image) VALUES (?, ?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssss", $param_name, $param_category, $param_address, $param_phone, $param_description, $param_image);
            
            $param_name = $name;
            $param_category = $category;
            $param_address = $address;
            $param_phone = $phone;
            $param_description = $description;
            $param_image = $image;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: companies.php");
                exit();
            } else{
                echo "عذراً، حدث خطأ ما. الرجاء المحاولة مرة أخرى لاحقاً.";
            }
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة شركة جديدة - لوحة التحكم</title>
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
        .form-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: #0077cc;
            outline: none;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
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
                <h1 class="page-title">إضافة شركة جديدة</h1>
            </div>
            
            <div class="form-container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>اسم الشركة</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                        <span class="error-message"><?php echo $name_err; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label>التصنيف</label>
                        <select name="category" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>">
                            <option value="">اختر التصنيف</option>
                            <option value="تكنولوجيا">تكنولوجيا</option>
                            <option value="صناعة">صناعة</option>
                            <option value="خدمات">خدمات</option>
                            <option value="تجارة">تجارة</option>
                            <option value="تعليم">تعليم</option>
                            <option value="صحة">صحة</option>
                            <option value="سياحة">سياحة</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                        <span class="error-message"><?php echo $category_err; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label>العنوان</label>
                        <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                        <span class="error-message"><?php echo $address_err; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label>رقم الهاتف</label>
                        <input type="tel" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                        <span class="error-message"><?php echo $phone_err; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label>وصف الشركة</label>
                        <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>صورة الشركة</label>
                        <input type="file" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                        <span class="error-message"><?php echo $image_err; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn-submit">إضافة الشركة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 