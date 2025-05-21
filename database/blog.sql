-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create posts table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(255),
    category_id INT,
    author_id INT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    INDEX idx_author (author_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create newsletter_subscribers table
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample categories
INSERT INTO categories (name, slug, description) VALUES
('التسويق الرقمي', 'marketing', 'مقالات عن التسويق الرقمي ووسائل التواصل الاجتماعي'),
('التصوير', 'photography', 'مقالات عن التصوير الفوتوغرافي وتقنياته'),
('الطباعة', 'printing', 'مقالات عن الطباعة وتقنياتها'),
('الأعمال', 'business', 'مقالات عن ريادة الأعمال وإدارة الشركات');

-- Insert sample posts
INSERT INTO posts (title, slug, content, excerpt, category_id, author_id, status) VALUES
('كيف تبدأ في التسويق الرقمي لعملك', 'how-to-start-digital-marketing', 'محتوى المقال الكامل...', 'دليلك الشامل للبدء في التسويق الرقمي وزيادة مبيعاتك عبر الإنترنت...', 1, 1, 'published'),
('نصائح لتصوير منتجات احترافية', 'tips-for-professional-product-photography', 'محتوى المقال الكامل...', 'تعرف على أهم النصائح والحيل لتصوير منتجات احترافية...', 2, 1, 'published'),
('أحدث تقنيات الطباعة في 2024', 'latest-printing-technologies-2024', 'محتوى المقال الكامل...', 'تعرف على أحدث تقنيات الطباعة التي ستغير مستقبل الصناعة...', 3, 1, 'published'),
('كيف تبدأ مشروعك الخاص', 'how-to-start-your-own-business', 'محتوى المقال الكامل...', 'دليلك الشامل لبدء مشروعك الخاص وتحقيق النجاح...', 4, 1, 'published'); 