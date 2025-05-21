<?php
// تعيين منطقة زمنية
date_default_timezone_set('Asia/Damascus');

// تعيين ترميز UTF-8
header('Content-Type: text/html; charset=utf-8');

// دالة للتحقق من وجود الصورة
function checkImage($path) {
    if (file_exists($path)) {
        $size = getimagesize($path);
        return [
            'exists' => true,
            'width' => $size[0] ?? 0,
            'height' => $size[1] ?? 0,
            'mime' => $size['mime'] ?? '',
            'size' => filesize($path)
        ];
    }
    return ['exists' => false];
}

// قائمة الصور للتحقق منها
$images = [
    'img/البنر_الرئيسي.jpg',
    'img/التسويق_الإلكتروني.jpg',
    'img/تصوير_الأعراس.jpg',
    'img/تصوير_المنتجات.jpg',
    'img/تواصل_معنا.jpg',
    'img/خدمات_الطباعة.jpg',
    'img/دليل_الشركات.jpg',
    'img/اعلان-1.png',
    'img/اعلان-2.png',
    'img/اعلان-3.png'
];

echo "<h1>تقرير حالة الصور</h1>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f2f2f2;'>";
echo "<th>المسار</th><th>الحالة</th><th>الأبعاد</th><th>نوع الملف</th><th>الحجم</th><th>الرابط</th></tr>";

foreach ($images as $image) {
    $result = checkImage($image);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($image) . "</td>";
    echo "<td>" . ($result['exists'] ? "✅ موجود" : "❌ غير موجود") . "</td>";
    echo "<td>" . ($result['exists'] ? $result['width'] . 'x' . $result['height'] : '-') . "</td>";
    echo "<td>" . ($result['exists'] ? $result['mime'] : '-') . "</td>";
    echo "<td>" . ($result['exists'] ? round($result['size'] / 1024, 2) . ' KB' : '-') . "</td>";
    echo "<td><a href='/" . $image . "' target='_blank'>فتح الصورة</a></td>";
    echo "</tr>";
}

echo "</table>";

// التحقق من إعدادات السيرفر
echo "<h2>إعدادات السيرفر</h2>";
echo "<ul>";
echo "<li>PHP Version: " . phpversion() . "</li>";
echo "<li>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</li>";
echo "<li>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "<li>Current Directory: " . __DIR__ . "</li>";
echo "</ul>";

// التحقق من الصلاحيات
echo "<h2>صلاحيات المجلدات</h2>";
echo "<ul>";
echo "<li>img/ permissions: " . substr(sprintf('%o', fileperms('img')), -4) . "</li>";
echo "<li>Current directory permissions: " . substr(sprintf('%o', fileperms('.')), -4) . "</li>";
echo "</ul>";

// إصلاح الصلاحيات
if (is_writable('img')) {
    echo "<h2>إصلاح الصلاحيات</h2>";
    echo "<p>جاري إصلاح صلاحيات الصور...</p>";
    
    foreach ($images as $image) {
        if (file_exists($image)) {
            chmod($image, 0644);
            echo "<p>تم إصلاح صلاحيات: " . htmlspecialchars($image) . "</p>";
        }
    }
    
    chmod('img', 0755);
    echo "<p>تم إصلاح صلاحيات مجلد الصور</p>";
} else {
    echo "<p style='color: red;'>لا يمكن تعديل صلاحيات المجلد. يرجى التحقق من صلاحيات المجلد يدوياً.</p>";
}
?> 