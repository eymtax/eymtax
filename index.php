<?php
require_once "admin/includes/config.php";
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Eymta X - خبراء التسويق الرقمي والتصوير والطباعة في سوريا">
    <meta name="keywords" content="تسويق رقمي, تصوير, طباعة, سوريا, خدمات رقمية">
    <title>Eymta X - خبراء التسويق الرقمي والتصوير والطباعة في سوريا</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Preload Fonts -->
    <link rel="preload" href="assets/fonts/Cairo-Regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/Cairo-Bold.woff2" as="font" type="font/woff2" crossorigin>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/images/logo.png" alt="Eymta X Logo" class="logo">
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">الرئيسية</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="marketing.php">التسويق الإلكتروني</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="photography.php">التصوير</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="printing.php">الطباعة</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="companies.php">دليل الشركات</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="blog.php">المدونة</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">اتصل بنا</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">نرفع أعمالك إلى مستوى جديد</h1>
                    <p class="hero-description">نقدم خدمات متكاملة في التسويق الرقمي، التصوير الاحترافي، والطباعة عالية الجودة</p>
                    <div class="hero-buttons">
                        <a href="contact.php" class="btn btn-primary">اطلب استشارة مجانية</a>
                        <a href="services.php" class="btn btn-outline-primary">تعرف على خدماتنا</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/hero-image.png" alt="Hero Image" class="hero-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <h2 class="section-title">خدماتنا</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3>التسويق الرقمي</h3>
                        <p>حلول تسويقية متكاملة لتعزيز حضورك الرقمي وزيادة مبيعاتك</p>
                        <a href="marketing.php" class="service-link">اكتشف المزيد</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h3>التصوير الاحترافي</h3>
                        <p>تصوير احترافي للمنتجات والأعراس مع التركيز على التفاصيل</p>
                        <a href="photography.php" class="service-link">اكتشف المزيد</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-print"></i>
                        </div>
                        <h3>خدمات الطباعة</h3>
                        <p>خدمات طباعة متكاملة لجميع احتياجاتك الشخصية والتجارية</p>
                        <a href="printing.php" class="service-link">اكتشف المزيد</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">لماذا تختار Eymta X؟</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="feature-item">
                        <i class="fas fa-medal"></i>
                        <h3>خبرة طويلة</h3>
                        <p>سنوات من الخبرة في تقديم خدمات عالية الجودة</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-item">
                        <i class="fas fa-users"></i>
                        <h3>فريق احترافي</h3>
                        <p>فريق عمل متخصص في جميع المجالات</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-item">
                        <i class="fas fa-tags"></i>
                        <h3>أسعار تنافسية</h3>
                        <p>عروض وباقات متنوعة تناسب جميع الميزانيات</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-item">
                        <i class="fas fa-headset"></i>
                        <h3>دعم فني</h3>
                        <p>دعم فني متواصل وخدمة عملاء على مدار الساعة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="portfolio">
        <div class="container">
            <h2 class="section-title">أعمالنا</h2>
            <div class="portfolio-grid">
                <!-- Portfolio items will be loaded dynamically -->
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">آراء عملائنا</h2>
            <div class="swiper testimonials-slider">
                <div class="swiper-wrapper">
                    <!-- Testimonials will be loaded dynamically -->
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="section-title">تواصل معنا</h2>
                    <form class="contact-form" id="contactForm">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="الاسم الكامل" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="البريد الإلكتروني" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" placeholder="رقم الهاتف" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="رسالتك" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">إرسال</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="contact-info">
                        <h3>معلومات التواصل</h3>
                        <ul>
                            <li><i class="fas fa-phone"></i> 0938029294</li>
                            <li><i class="fas fa-envelope"></i> info@eymtax.com</li>
                            <li><i class="fas fa-map-marker-alt"></i> سوريا</li>
                        </ul>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-info">
                        <img src="assets/images/logo-white.png" alt="Eymta X Logo" class="footer-logo">
                        <p>نرفع أعمالك إلى مستوى جديد من خلال خدمات متكاملة في التسويق الرقمي، التصوير، والطباعة</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-links">
                        <h3>روابط سريعة</h3>
                        <ul>
                            <li><a href="about.php">من نحن</a></li>
                            <li><a href="services.php">خدماتنا</a></li>
                            <li><a href="portfolio.php">أعمالنا</a></li>
                            <li><a href="blog.php">المدونة</a></li>
                            <li><a href="contact.php">اتصل بنا</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-newsletter">
                        <h3>النشرة البريدية</h3>
                        <p>اشترك في نشرتنا البريدية للحصول على آخر الأخبار والعروض</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="البريد الإلكتروني" required>
                            <button type="submit">اشتراك</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Eymta X. جميع الحقوق محفوظة</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <!-- JavaScript Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 