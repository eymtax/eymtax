<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="مدونة Eymta X - مقالات في التسويق الرقمي والتصوير والطباعة">
    <title>المدونة | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .blog-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/blog-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .search-box {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .blog-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
        }
        
        .blog-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .blog-card .card-body {
            padding: 1.5rem;
        }
        
        .blog-card .card-title {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .blog-card .card-text {
            color: #666;
            margin-bottom: 1rem;
        }
        
        .blog-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #888;
            font-size: 0.9rem;
        }
        
        .blog-meta i {
            color: var(--primary-color);
        }
        
        .category-filter {
            margin-bottom: 2rem;
        }
        
        .category-filter .btn {
            margin: 0.25rem;
        }
        
        .pagination {
            margin-top: 2rem;
        }
        
        .sidebar {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .sidebar h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .recent-posts {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .recent-posts li {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .recent-posts li:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .recent-posts a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .recent-posts a:hover {
            color: var(--primary-color);
        }
        
        .categories-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .categories-list li {
            margin-bottom: 0.5rem;
        }
        
        .categories-list a {
            color: #666;
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .categories-list a:hover {
            color: var(--primary-color);
        }
        
        .categories-list .badge {
            background: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Blog Hero Section -->
    <section class="blog-hero">
        <div class="container">
            <h1 class="display-4 mb-4">المدونة</h1>
            <p class="lead">أحدث المقالات والأخبار في مجالات التسويق الرقمي والتصوير والطباعة</p>
        </div>
    </section>

    <!-- Blog Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Search Box -->
                    <div class="search-box">
                        <form id="searchForm" class="row g-3">
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="searchInput" placeholder="ابحث عن مقال...">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">بحث</button>
                            </div>
                        </form>
                    </div>

                    <!-- Category Filter -->
                    <div class="category-filter text-center">
                        <button class="btn btn-outline-primary active" data-category="all">الكل</button>
                        <button class="btn btn-outline-primary" data-category="marketing">التسويق الرقمي</button>
                        <button class="btn btn-outline-primary" data-category="photography">التصوير</button>
                        <button class="btn btn-outline-primary" data-category="printing">الطباعة</button>
                        <button class="btn btn-outline-primary" data-category="business">الأعمال</button>
                    </div>

                    <!-- Blog Posts Grid -->
                    <div class="row g-4" id="blogPosts">
                        <!-- Blog Post Template -->
                        <div class="col-md-6">
                            <article class="blog-card">
                                <img src="assets/images/blog/post1.jpg" alt="Blog Post">
                                <div class="card-body">
                                    <h3 class="card-title">كيف تبدأ في التسويق الرقمي لعملك</h3>
                                    <p class="card-text">دليلك الشامل للبدء في التسويق الرقمي وزيادة مبيعاتك عبر الإنترنت...</p>
                                    <div class="blog-meta">
                                        <span><i class="far fa-calendar"></i> 15 مارس 2024</span>
                                        <span><i class="far fa-user"></i> أحمد محمد</span>
                                    </div>
                                    <a href="blog-post.php?id=1" class="btn btn-primary mt-3">اقرأ المزيد</a>
                                </div>
                            </article>
                        </div>
                        <!-- More blog posts will be loaded dynamically -->
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation" class="text-center">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">السابق</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">التالي</a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Recent Posts -->
                        <div class="mb-4">
                            <h3>أحدث المقالات</h3>
                            <ul class="recent-posts">
                                <li>
                                    <a href="blog-post.php?id=1">كيف تبدأ في التسويق الرقمي لعملك</a>
                                </li>
                                <li>
                                    <a href="blog-post.php?id=2">نصائح لتصوير منتجات احترافية</a>
                                </li>
                                <li>
                                    <a href="blog-post.php?id=3">أحدث تقنيات الطباعة في 2024</a>
                                </li>
                            </ul>
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <h3>التصنيفات</h3>
                            <ul class="categories-list">
                                <li>
                                    <a href="#">
                                        التسويق الرقمي
                                        <span class="badge">12</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        التصوير
                                        <span class="badge">8</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        الطباعة
                                        <span class="badge">6</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        الأعمال
                                        <span class="badge">4</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Newsletter -->
                        <div>
                            <h3>النشرة البريدية</h3>
                            <p>اشترك في نشرتنا البريدية للحصول على أحدث المقالات والأخبار</p>
                            <form id="newsletterForm" class="mt-3">
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="البريد الإلكتروني" required>
                                    <button class="btn btn-primary" type="submit">اشتراك</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        // Search and Filter Functionality
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = document.getElementById('searchInput').value;
            searchPosts(searchTerm);
        });

        // Category Filter Buttons
        document.querySelectorAll('.category-filter .btn').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.category-filter .btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                // Add active class to clicked button
                this.classList.add('active');
                
                const category = this.dataset.category;
                searchPosts(document.getElementById('searchInput').value, category);
            });
        });

        function searchPosts(searchTerm, category = 'all') {
            // Here you would typically make an AJAX call to your backend
            // For now, we'll just log the search parameters
            console.log('Searching for:', searchTerm, 'in category:', category);
            
            // You would then update the blog posts grid with the results
            // This is where you'd implement the actual search functionality
        }

        // Newsletter Form
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Here you would typically make an AJAX call to your backend
            // For now, we'll just log the email
            console.log('Newsletter subscription:', email);
            
            // Show success message
            showNotification('تم الاشتراك في النشرة البريدية بنجاح!', 'success');
            this.reset();
        });

        // Load Posts Function
        function loadPosts(page = 1) {
            // Here you would typically make an AJAX call to your backend
            // to load posts for the specified page
            console.log('Loading posts for page:', page);
            
            // You would then update the blog posts grid with the results
            // This is where you'd implement the actual pagination functionality
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadPosts();
        });
    </script>
</body>
</html> 