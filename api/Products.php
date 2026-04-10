<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$pdo = getDBConnection();

switch ($method) {
    case 'GET':
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $minPrice = $_GET['min_price'] ?? '';
        $maxPrice = $_GET['max_price'] ?? '';
        $sort = $_GET['sort'] ?? 'newest';
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 12;
        
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT p.*, c.name as category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_active = 1";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($category)) {
            $sql .= " AND p.category_id = ?";
            $params[] = $category;
        }
        
        if (!empty($minPrice)) {
            $sql .= " AND p.price >= ?";
            $params[] = $minPrice;
        }
        
        if (!empty($maxPrice)) {
            $sql .= " AND p.price <= ?";
            $params[] = $maxPrice;
        }
        
        // Sorting
        switch ($sort) {
            case 'price_low':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'popular':
                $sql .= " ORDER BY p.views DESC";
                break;
            case 'rating':
                $sql .= " ORDER BY p.rating DESC";
                break;
            default:
                $sql .= " ORDER BY p.created_at DESC";
        }
        
        // Pagination
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();
        
        // Get total count
        $countSql = str_replace("SELECT p.*, c.name as category_name", "SELECT COUNT(*)", $sql);
        $countSql = preg_replace('/LIMIT \? OFFSET \?/', '', $countSql);
        $countStmt = $pdo->prepare($countSql);
        $countParams = array_slice($params, 0, count($params) - 2);
        $countStmt->execute($countParams);
        $total = $countStmt->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'data' => $products,
            'pagination' => [
                'total' => $total,
                'page' => (int)$page,
                'limit' => (int)$limit,
                'pages' => ceil($total / $limit)
            ]
        ]);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>