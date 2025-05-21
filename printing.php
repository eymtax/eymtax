<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="خدمات الطباعة المتكاملة - Eymta X">
    <title>الطباعة | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .printing-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/printing-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s ease;
            text-align: center;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .product-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .printing-feature {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .printing-feature i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .price-list {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .price-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Printing Hero Section -->
    <section class="printing-hero">
        <div class="container">
            <h1 class="display-4 mb-4">خدمات الطباعة</h1>
            <p class="lead">طباعة احترافية لجميع احتياجاتك الشخصية والتجارية</p>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">منتجاتنا</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="assets/images/products/business-cards.jpg" alt="بطاقات عمل">
                        <h3>بطاقات عمل</h3>
                        <p>تصميم وطباعة بطاقات عمل احترافية بأفضل الخامات</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="assets/images/products/brochures.jpg" alt="بروشورات">
                        <h3>بروشورات وفلايرات</h3>
                        <p>تصميم وطباعة بروشورات وفلايرات عالية الجودة</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="assets/images/products/banners.jpg" alt="لافتات">
                        <h3>لافتات وبنرات</h3>
                        <p>طباعة لافتات وبنرات بأحجام مختلفة وخامات متنوعة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">مميزات خدماتنا</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="printing-feature">
                        <i class="fas fa-print"></i>
                        <h3>أحدث التقنيات</h3>
                        <p>نستخدم أحدث ماكينات الطباعة</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="printing-feature">
                        <i class="fas fa-palette"></i>
                        <h3>ألوان دقيقة</h3>
                        <p>طباعة بألوان دقيقة ومطابقة للأصل</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="printing-feature">
                        <i class="fas fa-clock"></i>
                        <h3>تسليم سريع</h3>
                        <p>تسليم الطلبات في أسرع وقت</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="printing-feature">
                        <i class="fas fa-hand-holding-usd"></i>
                        <h3>أسعار تنافسية</h3>
                        <p>أسعار منافسة مع ضمان الجودة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Price List Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">قائمة الأسعار</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="price-list">
                        <div class="price-item">
                            <div>
                                <h4>بطاقات عمل</h4>
                                <p>100 قطعة - طباعة وجهين</p>
                            </div>
                            <span class="price">50,000 ل.س</span>
                        </div>
                        <div class="price-item">
                            <div>
                                <h4>بروشورات</h4>
                                <p>100 قطعة - A4 مطوية</p>
                            </div>
                            <span class="price">75,000 ل.س</span>
                        </div>
                        <div class="price-item">
                            <div>
                                <h4>لافتات</h4>
                                <p>1×2 متر - طباعة عالية الدقة</p>
                            </div>
                            <span class="price">150,000 ل.س</span>
                        </div>
                        <div class="price-item">
                            <div>
                                <h4>كتالوجات</h4>
                                <p>50 قطعة - A4 ملونة</p>
                            </div>
                            <span class="price">200,000 ل.س</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4">جاهز لطباعة منتجاتك؟</h2>
            <p class="lead mb-4">تواصل معنا الآن للحصول على عرض سعر</p>
            <a href="contact.php" class="btn btn-primary btn-lg">اطلب عرض سعر</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 