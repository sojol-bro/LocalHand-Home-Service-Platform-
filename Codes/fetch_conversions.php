<?php
require 'db.php';

$user_id = $_GET['user_id']; // Or worker_id
$is_worker = $_GET['is_worker']; // Boolean flag to determine role

try {
    if ($is_worker) {
        $stmt = $pdo->prepare("SELECT * FROM messages WHERE worker_id = ? GROUP BY conversation_id");
        $stmt->execute([$user_id]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM messages WHERE user_id = ? GROUP BY conversation_id");
        $stmt->execute([$user_id]);
    }
    $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($conversations);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
