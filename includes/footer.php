<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="footer-info">
                    <img src="assets/images/logo-white.png" alt="<?php echo SITE_NAME; ?> Logo" class="footer-logo">
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
                    <form class="newsletter-form" id="newsletterForm">
                        <input type="email" name="email" placeholder="البريد الإلكتروني" required>
                        <button type="submit">اشتراك</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. جميع الحقوق محفوظة</p>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

<script>
// Newsletter Form Handler
document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    // Disable submit button and show loading state
    submitButton.disabled = true;
    submitButton.textContent = 'جاري الاشتراك...';
    
    fetch('api/newsletter.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم اشتراكك في النشرة البريدية بنجاح!', 'success');
            this.reset();
        } else {
            showNotification(data.message || 'حدث خطأ أثناء الاشتراك. يرجى المحاولة مرة أخرى.', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
});
</script> 