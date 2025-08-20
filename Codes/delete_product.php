<?php
session_start();
include('db.php');

header('Content-Type: application/json');

// Check if the worker is logged in
if (!isset($_SESSION['worker_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$worker_id = $_SESSION['worker_id'];

try {
    // Decode the JSON payload
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = isset($data['product_id']) ? (int) $data['product_id'] : 0;

    if ($product_id > 0) {
        // Verify that the product belongs to the logged-in worker
        $sql = "DELETE FROM market_products WHERE product_id = :product_id AND worker_id = :worker_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':worker_id', $worker_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete product.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
