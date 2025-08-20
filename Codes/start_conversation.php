<?php
require 'db.php';

$user_id = $_POST['user_id'];
$worker_id = $_POST['worker_id'];

try {
    // Check if conversation already exists
    $stmt = $pdo->prepare("SELECT id FROM messages WHERE user_id = ? AND worker_id = ?");
    $stmt->execute([$user_id, $worker_id]);
    $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$conversation) {
        // Create new conversation
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, worker_id, text, timestamp) VALUES (?, ?, '', NOW())");
        $stmt->execute([$user_id, $worker_id]);
        echo json_encode(["status" => "success", "message" => "Conversation started."]);
    } else {
        echo json_encode(["status" => "success", "message" => "Conversation exists."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
