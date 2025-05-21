<?php
require_once '../includes/config.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Get request parameters
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$category = isset($_GET['category']) ? sanitize_input($_GET['category']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 9; // Number of companies per page
$offset = ($page - 1) * $per_page;

try {
    // Build the query
    $query = "SELECT * FROM companies WHERE 1=1";
    $params = [];
    
    // Add search condition if search term exists
    if (!empty($search)) {
        $query .= " AND (name LIKE ? OR description LIKE ? OR address LIKE ?)";
        $search_term = "%$search%";
        $params[] = $search_term;
        $params[] = $search_term;
        $params[] = $search_term;
    }
    
    // Add category condition if category is selected
    if (!empty($category)) {
        $query .= " AND category = ?";
        $params[] = $category;
    }
    
    // Get total count for pagination
    $count_query = str_replace("SELECT *", "SELECT COUNT(*)", $query);
    $stmt = $pdo->prepare($count_query);
    $stmt->execute($params);
    $total_companies = $stmt->fetchColumn();
    $total_pages = ceil($total_companies / $per_page);
    
    // Add pagination
    $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    
    // Execute the query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the response
    $response = [
        'success' => true,
        'data' => [
            'companies' => $companies,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_companies' => $total_companies,
                'per_page' => $per_page
            ]
        ]
    ];
    
    echo json_encode($response);
    
} catch (PDOException $e) {
    // Log the error
    error_log("Database error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ أثناء جلب بيانات الشركات'
    ]);
} 