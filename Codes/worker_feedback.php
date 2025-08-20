<?php
session_start();
include('db.php');

// Check if worker is logged in
if (!isset($_SESSION['worker_id'])) {
    header("Location: login.php");
    exit();
}

// Get the worker ID from session
$worker_id = $_SESSION['worker_id'];

try {
    // Fetch all feedback for the logged-in worker
    $sql = "
        SELECT r.review_id, r.user_id, r.rating, r.review_text, r.created_at, 
               u.name AS user_name, u.email AS user_email
        FROM reviews r
        INNER JOIN users u ON r.user_id = u.user_id
        WHERE r.worker_id = :worker_id
        ORDER BY r.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['worker_id' => $worker_id]);
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color:rgb(25, 58, 91);
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #343a40;
        }

        .feedback {
            background-color: #e9ecef;
            margin: 15px 0;
            padding: 15px;
            border-left: 5px solid #007bff;
            border-radius: 5px;
        }

        .feedback h2 {
            margin: 0;
            color: #343a40;
        }

        .feedback p {
            margin: 5px 0;
            color: #495057;
        }

        .feedback .highlight {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
           <!-- <a href="#">Marketplace</a> -->
        </div>
        <div class="links">
            <a href="worker_dashboard.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1>Feedbacks</h1>
        
        <?php if (!empty($feedbacks)) { ?>
            <?php foreach ($feedbacks as $feedback) { ?>
                <div class="feedback">
                    <h2>Feedback ID: <?php echo htmlspecialchars($feedback['review_id'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p><span class="highlight">Client:</span> <?php echo htmlspecialchars($feedback['user_name'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($feedback['user_email'], ENT_QUOTES, 'UTF-8'); ?>)</p>
                    <p><span class="highlight">Rating:</span> <?php echo htmlspecialchars($feedback['rating'], ENT_QUOTES, 'UTF-8'); ?>/5</p>
                    <p><span class="highlight">Feedback:</span> <?php echo htmlspecialchars($feedback['review_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><span class="highlight">Date:</span> <?php echo htmlspecialchars($feedback['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p style="text-align: center; color: #6c757d;">No feedback available yet.</p>
        <?php } ?>
    </div>
</body>
</html>
