<?php
// تعيين منطقة زمنية
date_default_timezone_set('Asia/Damascus');

// تعيين ترميز UTF-8
header('Content-Type: text/html; charset=utf-8');

// دالة للتحقق من الصلاحيات
function checkPermissions($path) {
    $perms = fileperms($path);
    $perms = substr(sprintf('%o', $perms), -4);
    return $perms;
}

// دالة لإصلاح الصلاحيات
function fixPermissions($path) {
    if (is_dir($path)) {
        chmod($path, 0755);
    } else {
        chmod($path, 0644);
    }
}

// دالة للتحقق من نوع MIME
function checkMimeType($file) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file);
    finfo_close($finfo);
    return $mimeType;
}

// دالة للتحقق من الصور
function checkImages($dir) {
    $results = [];
    $files = scandir($dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            $results = array_merge($results, checkImages($path));
        } else {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                $results[] = [
                    'path' => $path,
                    'permissions' => checkPermissions($path),
                    'mime' => checkMimeType($path),
                    'size' => filesize($path),
                    'exists' => file_exists($path)
                ];
            }
        }
    }
    
    return $results;
}

// التحقق من الصور
$imageDir = __DIR__ . '/img';
$results = checkImages($imageDir);

// عرض النتائج
echo "<h1>تقرير حالة الصور</h1>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>المسار</th><th>الصلاحيات</th><th>نوع MIME</th><th>الحجم</th><th>الحالة</th></tr>";

foreach ($results as $result) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($result['path']) . "</td>";
    echo "<td>" . $result['permissions'] . "</td>";
    echo "<td>" . $result['mime'] . "</td>";
    echo "<td>" . round($result['size'] / 1024, 2) . " KB</td>";
    echo "<td>" . ($result['exists'] ? "موجود" : "غير موجود") . "</td>";
    echo "</tr>";
    
    // إصلاح الصلاحيات إذا كانت غير صحيحة
    if ($result['permissions'] != '0644' && $result['permissions'] != '0755') {
        fixPermissions($result['path']);
    }
}

echo "</table>";

// التحقق من إعدادات PHP
echo "<h2>إعدادات PHP</h2>";
echo "<ul>";
echo "<li>upload_max_filesize: " . ini_get('upload_max_filesize') . "</li>";
echo "<li>post_max_size: " . ini_get('post_max_size') . "</li>";
echo "<li>max_execution_time: " . ini_get('max_execution_time') . "</li>";
echo "<li>memory_limit: " . ini_get('memory_limit') . "</li>";
echo "</ul>";

// التحقق من وجود mod_rewrite
echo "<h2>حالة mod_rewrite</h2>";
echo "<p>mod_rewrite: " . (in_array('mod_rewrite', apache_get_modules()) ? "مفعل" : "غير مفعل") . "</p>";
?> 