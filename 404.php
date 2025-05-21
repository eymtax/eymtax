<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - الصفحة غير موجودة | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1;
        }
        .error-message {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: var(--text-color);
        }
        .error-description {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .back-home {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .back-home:hover {
            background-color: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="container">
            <div class="error-code">404</div>
            <h1 class="error-message">عذراً، الصفحة غير موجودة</h1>
            <p class="error-description">
                يبدو أن الصفحة التي تبحث عنها غير موجودة أو تم نقلها.
                يمكنك العودة إلى الصفحة الرئيسية أو استخدام البحث للعثور على ما تريد.
            </p>
            <a href="index.php" class="back-home">
                <i class="fas fa-home"></i>
                العودة إلى الصفحة الرئيسية
            </a>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 