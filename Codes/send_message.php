<?php
include 'db.php';
session_start();

$conversation_id = $_POST['conversation_id'];
$message = $_POST['message'];
$sender_id = $_SESSION['user_id'] ?? $_SESSION['worker_id'];
$sender_type = isset($_SESSION['user_id']) ? 'user' : 'worker';

$query = "INSERT INTO messages (conversation_id, sender_id, sender_type, message, created_at) 
          VALUES (:conversation_id, :sender_id, :sender_type, :message, NOW())";
$stmt = $pdo->prepare($query);
$stmt->execute([
    'conversation_id' => $conversation_id,
    'sender_id' => $sender_id,
    'sender_type' => $sender_type,
    'message' => $message
]);

header("Location: conversation.php?conversation_id=$conversation_id");
exit();
?>
