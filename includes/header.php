<?php
// تحديد الصفحة الحالية
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo.png" alt="<?php echo SITE_NAME; ?> Logo" class="logo">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>" href="index.php">
                            الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'marketing.php' ? 'active' : ''; ?>" href="marketing.php">
                            التسويق الإلكتروني
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'photography.php' ? 'active' : ''; ?>" href="photography.php">
                            التصوير
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'printing.php' ? 'active' : ''; ?>" href="printing.php">
                            الطباعة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'companies.php' ? 'active' : ''; ?>" href="companies.php">
                            دليل الشركات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'blog.php' ? 'active' : ''; ?>" href="blog.php">
                            المدونة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'contact.php' ? 'active' : ''; ?>" href="contact.php">
                            اتصل بنا
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header> 