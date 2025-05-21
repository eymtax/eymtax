<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="خدمات التسويق الإلكتروني المتكاملة - Eymta X">
    <title>التسويق الإلكتروني | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .marketing-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/marketing-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .service-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
        }
        
        .service-card i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .process-step {
            text-align: center;
            padding: 2rem;
        }
        
        .process-step .number {
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Marketing Hero Section -->
    <section class="marketing-hero">
        <div class="container">
            <h1 class="display-4 mb-4">التسويق الإلكتروني</h1>
            <p class="lead">نرفع أعمالك إلى مستوى جديد من خلال استراتيجيات تسويقية متكاملة</p>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">خدماتنا التسويقية</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-hashtag"></i>
                        <h3>إدارة وسائل التواصل الاجتماعي</h3>
                        <p>إدارة احترافية لحساباتك على منصات التواصل الاجتماعي مع محتوى جذاب وتفاعل مستمر</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-ad"></i>
                        <h3>الإعلانات الممولة</h3>
                        <p>تصميم وإدارة حملات إعلانية ممولة على مختلف المنصات لزيادة المبيعات والوصول</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card text-center">
                        <i class="fas fa-search"></i>
                        <h3>تحسين محركات البحث</h3>
                        <p>تحسين ظهور موقعك في نتائج البحث وزيادة الزيارات العضوية</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">كيف نعمل</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="number">1</div>
                        <h3>التحليل</h3>
                        <p>تحليل السوق والمنافسين وتحديد الأهداف</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="number">2</div>
                        <h3>التخطيط</h3>
                        <p>وضع استراتيجية تسويقية متكاملة</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="number">3</div>
                        <h3>التنفيذ</h3>
                        <p>تنفيذ الحملات التسويقية باحترافية</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="number">4</div>
                        <h3>المتابعة</h3>
                        <p>تحليل النتائج وتحسين الأداء</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="mb-4">جاهز لرفع أعمالك إلى مستوى جديد؟</h2>
            <p class="lead mb-4">تواصل معنا الآن للحصول على استشارة مجانية</p>
            <a href="contact.php" class="btn btn-primary btn-lg">اطلب استشارة مجانية</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 