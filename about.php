<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>من نحن | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="تعرف على Eymta X - شركة رائدة في مجال التسويق الرقمي والتصوير والطباعة في سوريا">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .about-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/about-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .about-section {
            padding: 80px 0;
        }
        
        .about-section h2 {
            color: var(--primary-color);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .about-content {
            line-height: 1.8;
            font-size: 18px;
            color: #666;
        }
        
        .about-image {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('assets/images/stats-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
        }
        
        .stat-item {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .stat-item .number {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .stat-item .label {
            font-size: 18px;
            color: #fff;
        }
        
        .values-section {
            padding: 80px 0;
        }
        
        .value-item {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        
        .value-item:hover {
            transform: translateY(-5px);
        }
        
        .value-item i {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .value-item h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .value-item p {
            color: #666;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1 class="display-4 mb-4">من نحن</h1>
            <p class="lead">نحن نساعد الشركات على النمو من خلال حلول تسويقية وإبداعية متكاملة</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="assets/images/about-company.jpg" alt="Eymta X" class="img-fluid about-image">
                </div>
                <div class="col-lg-6">
                    <h2>قصتنا</h2>
                    <div class="about-content">
                        <p>تأسست شركة Eymta X في عام 2020 بهدف تقديم حلول متكاملة في مجال التسويق الرقمي والتصوير والطباعة للشركات والأفراد في سوريا.</p>
                        <p>نحن نؤمن بأن نجاح عملائنا هو نجاحنا، لذلك نعمل دائماً على تقديم أفضل الخدمات بأعلى معايير الجودة وأحدث التقنيات.</p>
                        <p>فريقنا المكون من خبراء متخصصين في مجالات التسويق الرقمي والتصوير والطباعة يعمل بجد لضمان تحقيق أفضل النتائج لعملائنا.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="number">500+</div>
                        <div class="label">عميل سعيد</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="number">1000+</div>
                        <div class="label">مشروع منجز</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="number">50+</div>
                        <div class="label">خبير متخصص</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="number">4</div>
                        <div class="label">سنوات خبرة</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <h2 class="text-center mb-5">قيمنا</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-star"></i>
                        <h3>الجودة</h3>
                        <p>نلتزم بتقديم خدمات عالية الجودة تلبي توقعات عملائنا وتتجاوزها</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-lightbulb"></i>
                        <h3>الإبداع</h3>
                        <p>نبتكر حلولاً إبداعية وفريدة تساعد عملائنا على التميز</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-handshake"></i>
                        <h3>الموثوقية</h3>
                        <p>نلتزم بمواعيدنا ونفي بوعودنا لعملائنا</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 