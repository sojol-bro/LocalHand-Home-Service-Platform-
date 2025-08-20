<?php
include 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;  // For users
$worker_id = $_GET['worker_id'] ?? null; // Worker ID passed via URL

if (!$user_id && !$worker_id) {
    die('Access denied.');
}

$conversation_id = $_GET['conversation_id'] ?? null;

if (!$conversation_id) {
    // Check if a conversation exists
    $query = "SELECT conversation_id FROM conversations WHERE user_id = :user_id AND worker_id = :worker_id LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id, 'worker_id' => $worker_id]);
    $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($conversation) {
        $conversation_id = $conversation['conversation_id'];
    } else {
        // Create a new conversation
        $query = "INSERT INTO conversations (user_id, worker_id) VALUES (:user_id, :worker_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'worker_id' => $worker_id]);
        $conversation_id = $pdo->lastInsertId();
    }

    header("Location: conversation.php?conversation_id=$conversation_id");
    exit();
}

// Fetch messages
$query = "SELECT * FROM messages WHERE conversation_id = :conversation_id ORDER BY created_at ASC";
$stmt = $pdo->prepare($query);
$stmt->execute(['conversation_id' => $conversation_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user profile photo
$userQuery = "SELECT image FROM users WHERE user_id = :user_id";
$userStmt = $pdo->prepare($userQuery);
$userStmt->execute(['user_id' => $user_id]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Fetch worker profile photo
$workerQuery = "SELECT profile_photo FROM workers WHERE worker_id = :worker_id";
$workerStmt = $pdo->prepare($workerQuery);
$workerStmt->execute(['worker_id' => $worker_id]);
$worker = $workerStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #c3d7f4, #ffffff);
            margin: 0;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
            font-weight: bold;
        }

        #chat-window {
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .message {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .user-message {
            background-color: #d1e7ff;
            padding: 10px;
            border-radius: 8px;
            max-width: 70%;
            margin-left: auto;
            text-align: right;
        }

        .worker-message {
            background-color: #e6ffe6;
            padding: 10px;
            border-radius: 8px;
            max-width: 70%;
            margin-right: auto;
        }

        .message img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .message p {
            margin: 0;
            padding: 0;
        }

        .message small {
            display: block;
            font-size: 0.8em;
            color: #555;
        }

        form {
            display: flex;
            align-items: center;
        }

        textarea {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: none;
            margin-right: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .user-message img {
            order: 2;
            margin-left: 10px;
            margin-right: 0;
        }

        .worker-message img {
            order: 1;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #007bff;
            font-size: 1em;
            font-weight: bold;
        }

        .back-button:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="javascript:history.back()" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
    <h2>Messaging Portal</h2>

    <div id="chat-window">
        <?php foreach ($messages as $message): ?>
            <div class="message <?= $message['sender_type'] === 'user' ? 'user-message' : 'worker-message'; ?>">
                <img src="<?= htmlspecialchars($message['sender_type'] === 'user' ? ($user['image'] ?? 'default.png') : ($worker['profile_photo'] ?? 'default.png')); ?>" alt="">
                <div>
                    <p><?= htmlspecialchars($message['message']); ?></p>
                    <small><?= htmlspecialchars($message['created_at']); ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <form action="send_message.php" method="POST">
        <input type="hidden" name="conversation_id" value="<?= $conversation_id; ?>">
        <textarea name="message" placeholder="Type your message..." required></textarea>
        <button type="submit"><i class="fas fa-paper-plane"></i> Send</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatWindow = document.getElementById('chat-window');
        chatWindow.scrollTop = chatWindow.scrollHeight; // Scroll to the bottom of the chat
    });
</script>

</body>
</html>
