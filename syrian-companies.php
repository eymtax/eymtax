<?php
require_once "admin/includes/config.php";

// جلب جميع الشركات
$sql = "SELECT * FROM companies ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل الشركات السورية | Eymta X</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .companies-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .company-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .company-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .company-card h3 {
            color: #0077cc;
            margin-bottom: 0.5rem;
        }
        .company-card .category {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }
        .company-card .address, .company-card .phone {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }
        .company-card .desc {
            color: #444;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="index.html" style="color: white; text-decoration: none;">Eymta X</a></h1>
        <p>نرفع أعمالك إلى مستوى جديد</p>
        <nav>
            <a href="index.html">الرئيسية</a>
            <a href="marketing.html">التسويق الإلكتروني</a>
            <a href="product-photography.html">تصوير المنتجات</a>
            <a href="wedding-photography.html">تصوير الأعراس</a>
            <a href="printing.html">خدمات الطباعة</a>
            <a href="syrian-companies.php">دليل الشركات</a>
            <a href="blog.html">المدونة</a>
            <a href="about.html">من نحن</a>
            <a href="contact.html">اتصل بنا</a>
        </nav>
    </header>
    <main style="margin-top: 80px;">
        <section class="services">
            <h2>دليل الشركات السورية</h2>
            <div class="companies-list">
                <?php
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<div class="company-card">';
                        if($row['image']) {
                            echo '<img src="img/companies/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        } else {
                            echo '<img src="img/companies/default.png" alt="no image">';
                        }
                        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                        echo '<div class="category">' . htmlspecialchars($row['category']) . '</div>';
                        echo '<div class="address">' . htmlspecialchars($row['address']) . '</div>';
                        echo '<div class="phone">' . htmlspecialchars($row['phone']) . '</div>';
                        if($row['description']) {
                            echo '<div class="desc">' . nl2br(htmlspecialchars($row['description'])) . '</div>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p style="text-align:center;">لا توجد شركات مسجلة حالياً.</p>';
                }
                ?>
            </div>
        </section>
    </main>
    <footer>
        <p>Eymta X – نرفع أعمالك إلى مستوى جديد</p>
    </footer>
</body>
</html> 