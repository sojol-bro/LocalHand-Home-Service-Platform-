<?php
include 'db.php';
session_start();

$worker_id = $_SESSION['worker_id'];

// Fetch worker's conversations
$query = "SELECT c.conversation_id, u.name AS user_name
          FROM conversations c
          JOIN users u ON c.user_id = u.user_id
          WHERE c.worker_id = :worker_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['worker_id' => $worker_id]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Conversations</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #c3d7f4, #ffffff);
            margin: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2980b9;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 15px 0;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        li:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        a {
            text-decoration: none;
            color: #2980b9;
            font-size: 1.2em;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        a:hover {
            text-decoration: underline;
        }

        a svg {
            margin-right: 10px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2980b9;
            font-size: 1.1em;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Conversations</h2>

    <ul>
        <?php foreach ($conversations as $conversation): ?>
            <li>
                <a href="conversation.php?conversation_id=<?= $conversation['conversation_id']; ?>&worker_id=<?= $worker_id; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                        <path d="M2 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a7.94 7.94 0 0 1-3.468-.771C3.16 14.605 1.868 15 1.868 15s.6-1.416.885-3.008A7.93 7.93 0 0 1 2 8z"/>
                        <path d="M7.066 10.933a1.5 1.5 0 1 1 1.866 0 1.5 1.5 0 1 1-1.866 0zm3-3a1.5 1.5 0 1 1 1.866 0 1.5 1.5 0 1 1-1.866 0zm-6 0a1.5 1.5 0 1 1 1.866 0 1.5 1.5 0 1 1-1.866 0z"/>
                    </svg>
                    Chat with <?= htmlspecialchars($conversation['user_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="worker_dashboard.php" class="back-link">&larr; Back to Dashboard</a>
</div>

</body>
</html>
