<?php
include 'db.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!$user_id) {
    die('Access denied. Please log in.');
}

// Fetch conversations for the user
$query = "SELECT c.conversation_id, w.name AS worker_name 
          FROM conversations c 
          JOIN workers w ON c.worker_id = w.worker_id 
          WHERE c.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Conversations</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 15px 0;
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        li:hover {
            background-color: #e0e0e0;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-size: 1.2em;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-size: 1.1em;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Your Conversations</h1>

    <ul>
        <?php foreach ($conversations as $conversation): ?>
            <li>
                <a href="conversation.php?conversation_id=<?= $conversation['conversation_id']; ?>">
                    Conversation with <?= htmlspecialchars($conversation['worker_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="user_dashboard.php" class="back-link">Back to Dashboard</a>
</div>

</body>
</html>
