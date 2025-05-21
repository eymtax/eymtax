-- Create companies table
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    logo VARCHAR(255),
    category VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    website VARCHAR(255),
    status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO companies (name, description, category, address, phone, email, website, status) VALUES
('شركة التقنية المتقدمة', 'شركة متخصصة في تطوير البرمجيات وتكنولوجيا المعلومات', 'technology', 'دمشق، شارع بغداد', '0938029294', 'info@techcompany.com', 'www.techcompany.com', 'active'),
('مؤسسة البناء الحديث', 'شركة مقاولات متخصصة في البناء والتشييد', 'construction', 'حلب، شارع الرئيسي', '0938029295', 'info@construction.com', 'www.construction.com', 'active'),
('مطعم الشرق', 'مطعم يقدم أشهى المأكولات الشرقية', 'food', 'دمشق، شارع الميدان', '0938029296', 'info@restaurant.com', 'www.restaurant.com', 'active'),
('متجر الأزياء', 'متجر متخصص في بيع الملابس والأزياء', 'retail', 'حلب، شارع التجاري', '0938029297', 'info@fashion.com', 'www.fashion.com', 'active'),
('شركة الخدمات العامة', 'شركة تقدم خدمات متنوعة للأفراد والشركات', 'services', 'دمشق، شارع الصناعة', '0938029298', 'info@services.com', 'www.services.com', 'active'); 