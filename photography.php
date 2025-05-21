<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="خدمات التصوير الاحترافية - Eymta X">
    <title>التصوير | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .photography-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/photography-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .portfolio-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .portfolio-item img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .portfolio-item:hover img {
            transform: scale(1.1);
        }
        
        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .portfolio-item:hover .portfolio-overlay {
            opacity: 1;
        }
        
        .portfolio-overlay h3 {
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        .service-feature {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .service-feature i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Photography Hero Section -->
    <section class="photography-hero">
        <div class="container">
            <h1 class="display-4 mb-4">التصوير الاحترافي</h1>
            <p class="lead">نحول لحظاتك إلى ذكريات خالدة من خلال تصوير احترافي</p>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">خدمات التصوير</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="service-feature">
                        <i class="fas fa-ring"></i>
                        <h3>تصوير الأعراس</h3>
                        <p>توثيق كامل ليوم زفافك مع فريق احترافي من المصورين</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-feature">
                        <i class="fas fa-box"></i>
                        <h3>تصوير المنتجات</h3>
                        <p>تصوير احترافي للمنتجات مع التركيز على التفاصيل</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-feature">
                        <i class="fas fa-building"></i>
                        <h3>تصوير العقارات</h3>
                        <p>تصوير داخلي وخارجي للعقارات بأحدث التقنيات</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">معرض أعمالنا</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="portfolio-item">
                        <img src="assets/images/portfolio/wedding-1.jpg" alt="تصوير أعراس">
                        <div class="portfolio-overlay">
                            <h3>تصوير الأعراس</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="portfolio-item">
                        <img src="assets/images/portfolio/product-1.jpg" alt="تصوير منتجات">
                        <div class="portfolio-overlay">
                            <h3>تصوير المنتجات</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="portfolio-item">
                        <img src="assets/images/portfolio/realestate-1.jpg" alt="تصوير عقارات">
                        <div class="portfolio-overlay">
                            <h3>تصوير العقارات</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">لماذا تختارنا؟</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="service-feature">
                        <i class="fas fa-camera"></i>
                        <h3>أحدث المعدات</h3>
                        <p>نستخدم أحدث كاميرات وأجهزة التصوير</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-feature">
                        <i class="fas fa-users"></i>
                        <h3>فريق محترف</h3>
                        <p>فريق من المصورين المحترفين</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-feature">
                        <i class="fas fa-magic"></i>
                        <h3>معالجة احترافية</h3>
                        <p>معالجة وتحرير الصور بأحدث البرامج</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-feature">
                        <i class="fas fa-clock"></i>
                        <h3>تسليم سريع</h3>
                        <p>تسليم الصور في أسرع وقت ممكن</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4">جاهز لتصوير لحظاتك المميزة؟</h2>
            <p class="lead mb-4">تواصل معنا الآن لحجز جلسة تصوير</p>
            <a href="contact.php" class="btn btn-primary btn-lg">احجز الآن</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 