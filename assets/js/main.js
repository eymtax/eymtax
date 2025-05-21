// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initBackToTop();
    initTestimonialsSlider();
    initContactForm();
    initPortfolioGrid();
});

// Back to Top Button
function initBackToTop() {
    const backToTopButton = document.querySelector('.back-to-top');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('active');
        } else {
            backToTopButton.classList.remove('active');
        }
    });

    backToTopButton.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Testimonials Slider
function initTestimonialsSlider() {
    const testimonialsSlider = new Swiper('.testimonials-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
    });
}

// Contact Form
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'جاري الإرسال...';
            
            // Send form data to server
            fetch('api/contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم إرسال رسالتك بنجاح!', 'success');
                    contactForm.reset();
                } else {
                    showNotification('حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.', 'error');
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
    }
}

// Portfolio Grid
function initPortfolioGrid() {
    const portfolioGrid = document.querySelector('.portfolio-grid');
    
    if (portfolioGrid) {
        // Load portfolio items
        fetch('api/portfolio.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderPortfolioItems(data.items);
                }
            })
            .catch(error => {
                console.error('Error loading portfolio items:', error);
            });
    }
}

// Render Portfolio Items
function renderPortfolioItems(items) {
    const portfolioGrid = document.querySelector('.portfolio-grid');
    
    if (portfolioGrid && items) {
        portfolioGrid.innerHTML = items.map(item => `
            <div class="portfolio-item">
                <img src="${item.image}" alt="${item.title}">
                <div class="portfolio-overlay">
                    <h3>${item.title}</h3>
                    <p>${item.description}</p>
                    <a href="${item.link}" class="portfolio-link">عرض التفاصيل</a>
                </div>
            </div>
        `).join('');
    }
}

// Show Notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Smooth Scroll for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Mobile Menu Toggle
const mobileMenuButton = document.querySelector('.navbar-toggler');
const mobileMenu = document.querySelector('.navbar-collapse');

if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('show');
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            mobileMenu.classList.remove('show');
        }
    });
}

// Add active class to current navigation item
function setActiveNavItem() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Call setActiveNavItem on page load
setActiveNavItem();

// Companies Directory Functions
function searchCompanies(searchTerm, category, page = 1) {
    const url = new URL('api/companies.php', window.location.origin);
    url.searchParams.append('search', searchTerm);
    url.searchParams.append('category', category);
    url.searchParams.append('page', page);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCompanies(data.data.companies);
                updatePagination(data.data.pagination);
            } else {
                showNotification('حدث خطأ أثناء البحث عن الشركات', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('حدث خطأ أثناء البحث عن الشركات', 'error');
        });
}

function renderCompanies(companies) {
    const grid = document.getElementById('companiesGrid');
    if (!grid) return;

    grid.innerHTML = companies.map(company => `
        <div class="col-md-4">
            <div class="company-card">
                <img src="${company.logo || 'assets/images/companies/default.jpg'}" alt="${company.name}" class="company-logo">
                <h3>${company.name}</h3>
                <div class="company-info">
                    <p><i class="fas fa-map-marker-alt"></i> ${company.address}</p>
                    <p><i class="fas fa-phone"></i> ${company.phone}</p>
                    <p><i class="fas fa-envelope"></i> ${company.email}</p>
                    ${company.website ? `<p><i class="fas fa-globe"></i> ${company.website}</p>` : ''}
                </div>
                <div class="mt-3">
                    <span class="badge bg-primary">${company.category}</span>
                </div>
            </div>
        </div>
    `).join('');
}

function updatePagination(pagination) {
    const paginationElement = document.querySelector('.pagination');
    if (!paginationElement) return;

    let html = `
        <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${pagination.current_page - 1}">السابق</a>
        </li>
    `;

    for (let i = 1; i <= pagination.total_pages; i++) {
        html += `
            <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
    }

    html += `
        <li class="page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${pagination.current_page + 1}">التالي</a>
        </li>
    `;

    paginationElement.innerHTML = html;

    // Add click event listeners to pagination links
    paginationElement.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = parseInt(this.dataset.page);
            if (!isNaN(page)) {
                const searchTerm = document.getElementById('searchInput').value;
                const category = document.getElementById('categorySelect').value;
                searchCompanies(searchTerm, category, page);
            }
        });
    });
}

// Blog Functions
function searchPosts(searchTerm, category = 'all', page = 1) {
    const url = new URL('api/blog.php', window.location.origin);
    url.searchParams.append('search', searchTerm);
    url.searchParams.append('category', category);
    url.searchParams.append('page', page);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderPosts(data.data.posts);
                updatePagination(data.data.pagination);
            } else {
                showNotification('حدث خطأ أثناء البحث عن المقالات', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('حدث خطأ أثناء البحث عن المقالات', 'error');
        });
}

function renderPosts(posts) {
    const grid = document.getElementById('blogPosts');
    if (!grid) return;

    grid.innerHTML = posts.map(post => `
        <div class="col-md-6">
            <article class="blog-card">
                <img src="${post.featured_image || 'assets/images/blog/default.jpg'}" alt="${post.title}">
                <div class="card-body">
                    <h3 class="card-title">${post.title}</h3>
                    <p class="card-text">${post.excerpt}</p>
                    <div class="blog-meta">
                        <span><i class="far fa-calendar"></i> ${formatDate(post.created_at)}</span>
                        <span><i class="far fa-user"></i> ${post.author_name}</span>
                    </div>
                    <a href="blog-post.php?slug=${post.slug}" class="btn btn-primary mt-3">اقرأ المزيد</a>
                </div>
            </article>
        </div>
    `).join('');
}

function updatePagination(pagination) {
    const paginationElement = document.querySelector('.pagination');
    if (!paginationElement) return;

    let html = `
        <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${pagination.current_page - 1}">السابق</a>
        </li>
    `;

    for (let i = 1; i <= pagination.total_pages; i++) {
        html += `
            <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
    }

    html += `
        <li class="page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${pagination.current_page + 1}">التالي</a>
        </li>
    `;

    paginationElement.innerHTML = html;

    // Add click event listeners to pagination links
    paginationElement.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = parseInt(this.dataset.page);
            if (!isNaN(page)) {
                const searchTerm = document.getElementById('searchInput').value;
                const category = document.querySelector('.category-filter .btn.active').dataset.category;
                searchPosts(searchTerm, category, page);
            }
        });
    });
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('ar-SA', options);
}

// Newsletter Subscription
function subscribeToNewsletter(email) {
    const formData = new FormData();
    formData.append('email', email);

    fetch('api/newsletter.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم الاشتراك في النشرة البريدية بنجاح!', 'success');
        } else {
            showNotification(data.message || 'حدث خطأ أثناء الاشتراك', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('حدث خطأ أثناء الاشتراك', 'error');
    });
} 