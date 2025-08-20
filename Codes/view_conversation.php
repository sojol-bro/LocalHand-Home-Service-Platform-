<?php
require 'db.php';

$conversation_id = $_GET['conversation_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE conversation_id = ? ORDER BY timestamp ASC");
    $stmt->execute([$conversation_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
