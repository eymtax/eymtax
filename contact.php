<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="تواصل مع Eymta X - خبراء التسويق الرقمي والتصوير والطباعة في سوريا">
    <title>اتصل بنا | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .contact-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/contact-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .contact-info-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }
        
        .contact-info-card:hover {
            transform: translateY(-5px);
        }
        
        .contact-info-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .contact-form {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .form-control {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 119, 204, 0.25);
        }
        
        .map-container {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 3rem;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <h1 class="display-4 mb-4">تواصل معنا</h1>
            <p class="lead">نحن هنا لمساعدتك في تحقيق أهدافك</p>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="contact-info-card text-center">
                        <i class="fas fa-phone"></i>
                        <h3>اتصل بنا</h3>
                        <p>0938029294</p>
                        <p>info@eymtax.com</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-card text-center">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>موقعنا</h3>
                        <p>سوريا</p>
                        <p>دمشق</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-card text-center">
                        <i class="fas fa-clock"></i>
                        <h3>ساعات العمل</h3>
                        <p>السبت - الخميس: 9:00 صباحاً - 6:00 مساءً</p>
                        <p>الجمعة: مغلق</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-form">
                        <h2 class="mb-4">أرسل لنا رسالة</h2>
                        <form id="contactForm" action="api/contact.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="الاسم الكامل" required>
                            </div>
                            
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="البريد الإلكتروني" required>
                            </div>
                            
                            <div class="form-group">
                                <input type="tel" class="form-control" name="phone" placeholder="رقم الهاتف" required>
                            </div>
                            
                            <div class="form-group">
                                <select class="form-control" name="subject" required>
                                    <option value="">اختر الموضوع</option>
                                    <option value="تسويق">التسويق الرقمي</option>
                                    <option value="تصوير">التصوير الاحترافي</option>
                                    <option value="طباعة">خدمات الطباعة</option>
                                    <option value="استفسار">استفسار عام</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="5" placeholder="رسالتك" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-paper-plane"></i>
                                إرسال الرسالة
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3329.5!2d36.3!3d33.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzPCsDMwJzAwLjAiTiAzNsKwMTgnMDAuMCJF!5e0!3m2!1sen!2s!4v1234567890" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Media Section -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="mb-4">تابعنا على وسائل التواصل الاجتماعي</h2>
            <div class="social-links">
                <a href="<?php echo FACEBOOK_URL; ?>" class="btn btn-outline-primary btn-lg mx-2">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="<?php echo INSTAGRAM_URL; ?>" class="btn btn-outline-primary btn-lg mx-2">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="<?php echo TWITTER_URL; ?>" class="btn btn-outline-primary btn-lg mx-2">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="<?php echo LINKEDIN_URL; ?>" class="btn btn-outline-primary btn-lg mx-2">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        // Form Submission Handler
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Disable submit button and show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
            
            fetch('api/contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.', 'success');
                    this.reset();
                } else {
                    showNotification(data.message || 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.', 'error');
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.', 'error');
                console.error('Error:', error);
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    </script>
</body>
</html> 