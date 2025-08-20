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
    // Fetch all completed jobs for the logged-in worker
    $sql = "
        SELECT b.booking_id, b.user_id, b.booking_date, b.status, b.description, 
               u.name AS user_name, u.email AS user_email 
        FROM bookings b
        INNER JOIN users u ON b.user_id = u.user_id
        WHERE b.worker_id = :worker_id AND b.status = 'Accepted'
        ORDER BY b.booking_date DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['worker_id' => $worker_id]);
    $completedJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous Work</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .navbar {
            background-color:rgb(28, 62, 97);
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

        .job {
            background-color: #e9ecef;
            margin: 15px 0;
            padding: 15px;
            border-left: 5px solid #28a745;
            border-radius: 5px;
        }

        .job h2 {
            margin: 0;
            color: #343a40;
        }

        .job p {
            margin: 5px 0;
            color: #495057;
        }

        .job .highlight {
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <!--<a href="#">Marketplace</a> -->
        </div>
        <div class="links">
            <a href="worker_dashboard.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1>Previous Work</h1>
        
        <?php if (!empty($completedJobs)) { ?>
            <?php foreach ($completedJobs as $job) { ?>
                <div class="job">
                    <h2>Job ID: <?php echo htmlspecialchars($job['booking_id'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p><span class="highlight">Client:</span> <?php echo htmlspecialchars($job['user_name'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($job['user_email'], ENT_QUOTES, 'UTF-8'); ?>)</p>
                    <p><span class="highlight">Booking Date:</span> <?php echo htmlspecialchars($job['booking_date'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><span class="highlight">Description:</span> <?php echo htmlspecialchars($job['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p style="text-align: center; color: #6c757d;">No completed work found.</p>
        <?php } ?>
    </div>
</body>
</html>
