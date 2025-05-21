<?php
require_once "admin/includes/config.php";
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="https://eymtax.com/">
    <title>Eymta X - خبراء التسويق الرقمي والتصوير والطباعة في سوريا</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="js/chatbot.js"></script>
    <style>
        /* Mobile First Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
            -webkit-text-size-adjust: 100%;
        }

        html {
            font-size: 14px;
            -webkit-text-size-adjust: 100%;
            scroll-behavior: smooth;
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
            color: #333;
            background-color: #f8f9fa;
            overflow-x: hidden;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Header Styles */
        header {
            background-color: #111;
            padding: 0.8rem;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header h1 {
            color: white;
            margin: 0;
            font-size: 1.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        header p {
            color: #ccc;
            margin: 0.2rem 0;
            font-size: 0.8rem;
            width: 100%;
        }

        nav {
            margin-top: 0.3rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.3rem;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 0.3rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
            background-color: rgba(255,255,255,0.1);
            display: inline-block;
            flex-shrink: 0;
        }

        /* Main Content */
        main {
            flex: 1;
            margin-top: 70px;
            width: 100%;
            padding: 0.5rem;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('img/البنر_الرئيسي.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 20px;
            position: relative;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .welcome-text {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: fadeInDown 1s ease-out;
        }

        .hero-description {
            font-size: 1.5rem;
            margin-bottom: 30px;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 1s ease-out;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 40px;
            background-color: #0077cc;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            animation: fadeIn 1.5s ease-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cta-button:hover {
            background-color: #005fa3;
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Services Section */
        .services {
            padding: 0.8rem;
            width: 100%;
            overflow-x: hidden;
        }

        .services h2 {
            text-align: center;
            color: #111;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            word-wrap: break-word;
        }

        .services-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.8rem;
            margin-bottom: 1rem;
            width: 100%;
        }

        .service-card {
            background: white;
            border-radius: 6px;
            padding: 0.8rem;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            width: 100%;
            overflow: hidden;
        }

        .service-card img {
            width: 100%;
            height: auto;
            border-radius: 6px;
            margin-bottom: 0.8rem;
            max-width: 100%;
        }

        .service-card h3 {
            color: #0077cc;
            margin-bottom: 0.4rem;
            font-size: 1.1rem;
            word-wrap: break-word;
        }

        .service-card p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 0.8rem;
        }

        .service-card a {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #0077cc;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }

        .service-card a:hover {
            background-color: #005fa3;
        }

        /* Footer */
        footer {
            background-color: #111;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            html {
                font-size: 16px;
            }

            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            nav {
                flex-wrap: nowrap;
            }

            nav a {
                font-size: 0.9rem;
            }
        }

        @media (min-width: 1024px) {
            .services-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            nav a {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="index.php" style="color: white; text-decoration: none;">Eymta X</a></h1>
        <p style="color: #ccc; margin: 0.2rem 0; font-size: 0.8rem; width: 100%;">أفضل شركة تسويق في سورية</p>
        <nav>
            <a href="index.php">الرئيسية</a>
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

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1 class="welcome-text">مرحباً بك في Eymta X</h1>
                <p class="hero-description">نقدم خدمات التسويق الرقمي والتصوير والطباعة بأعلى جودة</p>
                <a href="contact.html" class="cta-button">تواصل معنا</a>
            </div>
        </section>

        <section class="services">
            <h2>خدماتنا</h2>
            <div class="services-grid">
                <div class="service-card">
                    <img src="img/marketing.jpg" alt="التسويق الإلكتروني">
                    <h3>التسويق الإلكتروني</h3>
                    <p>نقدم خدمات التسويق الرقمي الشاملة لمساعدة شركتك على النمو</p>
                    <a href="marketing.html">المزيد</a>
                </div>
                <div class="service-card">
                    <img src="img/product-photography.jpg" alt="تصوير المنتجات">
                    <h3>تصوير المنتجات</h3>
                    <p>تصوير احترافي لمنتجاتك بأحدث التقنيات</p>
                    <a href="product-photography.html">المزيد</a>
                </div>
                <div class="service-card">
                    <img src="img/wedding-photography.jpg" alt="تصوير الأعراس">
                    <h3>تصوير الأعراس</h3>
                    <p>توثيق لحظاتك الخاصة بأسلوب عصري وإبداعي</p>
                    <a href="wedding-photography.html">المزيد</a>
                </div>
                <div class="service-card">
                    <img src="img/printing.jpg" alt="خدمات الطباعة">
                    <h3>خدمات الطباعة</h3>
                    <p>طباعة عالية الجودة لجميع احتياجاتك</p>
                    <a href="printing.html">المزيد</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>Eymta X – نرفع أعمالك إلى مستوى جديد</p>
    </footer>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html> 