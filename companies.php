<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="دليل الشركات السورية - Eymta X">
    <title>دليل الشركات | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .companies-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/companies-bg.jpg');
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
        
        .company-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }
        
        .company-card:hover {
            transform: translateY(-5px);
        }
        
        .company-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 1rem;
        }
        
        .company-info {
            margin-top: 1rem;
        }
        
        .company-info p {
            margin-bottom: 0.5rem;
        }
        
        .company-info i {
            color: var(--primary-color);
            margin-left: 0.5rem;
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
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Companies Hero Section -->
    <section class="companies-hero">
        <div class="container">
            <h1 class="display-4 mb-4">دليل الشركات السورية</h1>
            <p class="lead">اكتشف أفضل الشركات والمؤسسات في مختلف القطاعات</p>
        </div>
    </section>

    <!-- Search Section -->
    <section class="py-5">
        <div class="container">
            <div class="search-box">
                <form id="searchForm" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="searchInput" placeholder="ابحث عن شركة...">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="categorySelect">
                            <option value="">جميع القطاعات</option>
                            <option value="technology">تقنية المعلومات</option>
                            <option value="construction">البناء والمقاولات</option>
                            <option value="food">المطاعم والضيافة</option>
                            <option value="retail">التجارة والبيع بالتجزئة</option>
                            <option value="services">الخدمات</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">بحث</button>
                    </div>
                </form>
            </div>

            <!-- Category Filter -->
            <div class="category-filter text-center">
                <button class="btn btn-outline-primary active" data-category="all">الكل</button>
                <button class="btn btn-outline-primary" data-category="technology">تقنية المعلومات</button>
                <button class="btn btn-outline-primary" data-category="construction">البناء والمقاولات</button>
                <button class="btn btn-outline-primary" data-category="food">المطاعم والضيافة</button>
                <button class="btn btn-outline-primary" data-category="retail">التجارة والبيع بالتجزئة</button>
                <button class="btn btn-outline-primary" data-category="services">الخدمات</button>
            </div>

            <!-- Companies Grid -->
            <div class="row g-4" id="companiesGrid">
                <!-- Company Card Template -->
                <div class="col-md-4">
                    <div class="company-card">
                        <img src="assets/images/companies/company1.jpg" alt="Company Logo" class="company-logo">
                        <h3>شركة التقنية المتقدمة</h3>
                        <div class="company-info">
                            <p><i class="fas fa-map-marker-alt"></i> دمشق، سوريا</p>
                            <p><i class="fas fa-phone"></i> 0938029294</p>
                            <p><i class="fas fa-envelope"></i> info@company.com</p>
                            <p><i class="fas fa-globe"></i> www.company.com</p>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-primary">تقنية المعلومات</span>
                        </div>
                    </div>
                </div>
                <!-- More company cards will be loaded dynamically -->
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
    </section>

    <!-- Add Company CTA -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4">هل تريد إضافة شركتك إلى الدليل؟</h2>
            <p class="lead mb-4">سجل شركتك الآن ووصل إلى عملاء جدد</p>
            <a href="contact.php" class="btn btn-primary btn-lg">أضف شركتك</a>
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
            const category = document.getElementById('categorySelect').value;
            searchCompanies(searchTerm, category);
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
                if (category === 'all') {
                    document.getElementById('categorySelect').value = '';
                } else {
                    document.getElementById('categorySelect').value = category;
                }
                
                searchCompanies(
                    document.getElementById('searchInput').value,
                    document.getElementById('categorySelect').value
                );
            });
        });

        function searchCompanies(searchTerm, category) {
            // Here you would typically make an AJAX call to your backend
            // For now, we'll just log the search parameters
            console.log('Searching for:', searchTerm, 'in category:', category);
            
            // You would then update the companies grid with the results
            // This is where you'd implement the actual search functionality
        }

        // Load Companies Function
        function loadCompanies(page = 1) {
            // Here you would typically make an AJAX call to your backend
            // to load companies for the specified page
            console.log('Loading companies for page:', page);
            
            // You would then update the companies grid with the results
            // This is where you'd implement the actual pagination functionality
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCompanies();
        });
    </script>
</body>
</html> 