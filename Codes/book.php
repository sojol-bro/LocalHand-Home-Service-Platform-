<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to book a worker.";
    exit;
}

$message = ""; // Variable to hold success or error messages

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['worker_id'])) {
    $worker_id = $_POST['worker_id'];
    $Description = $_POST['Description'];
    $user_id = $_SESSION['user_id'];
    $date = date('Y-m-d'); // Current date for booking
    $status = "Pending"; // Default status

    // Ensure user_id exists in the users table before booking
    $check_user_query = "SELECT user_id FROM users WHERE user_id = :user_id";
    $check_stmt = $pdo->prepare($check_user_query);
    $check_stmt->execute([':user_id' => $user_id]);

    if ($check_stmt->rowCount() > 0) {
        // Insert the booking into the database using PDO
        $query = "INSERT INTO bookings (user_id, worker_id, booking_date, status, Description) 
                  VALUES (:user_id, :worker_id, :booking_date, :status, :Description)";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([
            ':user_id' => $user_id,
            ':worker_id' => $worker_id,
            ':booking_date' => $date,
            ':status' => $status,
            ':Description' => $Description
        ])) {
            $message = "Booking confirmed!";
        } else {
            $message = "Error: " . implode(" ", $stmt->errorInfo());
        }
    } else {
        $message = "Error: Invalid user ID.";
    }
}

// Fetch distinct job types from the database using PDO
$job_types_query = "SELECT DISTINCT specialty FROM workers";
$job_types_result = $pdo->query($job_types_query);

// Handle the location filter after a job type is selected
$workers_result = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['job_type'])) {
    $job_type = $_POST['job_type'];
    $address = $_POST['address'];

    // Using prepared statements with PDO for security
    $workers_query = "SELECT worker_id, name, specialty, address, hourly_rate 
                      FROM workers 
                      WHERE specialty = :job_type AND address LIKE :address";
    $stmt = $pdo->prepare($workers_query);
    $stmt->execute([
        ':job_type' => $job_type,
        ':address' => "%$address%"
    ]);
    $workers_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Worker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6fb;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: row;
            margin: 0;
        }

        .sidebar {
            width: 300px;
            background: linear-gradient(145deg, #467187, #467187);
            color: white;
            height: 100vh;
            padding: 20px;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .booking-panel {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: auto;
        }

        .booking-panel h2 {
            color: #34495e;
            font-size: 28px;
            margin-bottom: 20px;
        }

        select, input[type="text"], textarea, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        button {
            background: #467187;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #4e5db3;
        }

        .message {
            margin-top: 20px;
            color: #2ecc71;
            font-size: 16px;
            text-align: center;
        }

        .worker {
            margin-top: 30px;
        }

        .worker h3 {
            color: #34495e;
            font-size: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>Local-Hand</h1>
        <ul>
            <li><a href="user_dashboard.php">Dashboard</a></li>
            <li><a href="service.php">Services</a></li>
            <li><a href="booking_history.php">Bookings</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="booking-panel">
            <form method="POST">
                <h2>Select Job Type</h2>
                <select name="job_type" required>
                    <option value="" disabled selected>Select a job type</option>
                    <?php
                    if ($job_types_result && $job_types_result->rowCount() > 0) {
                        while ($job = $job_types_result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($job['specialty']) . "'>" . htmlspecialchars($job['specialty']) . "</option>";
                        }
                    }
                    ?>
                </select>
                <input type="text" name="address" placeholder="Enter location (optional)">
                <button type="submit">Search</button>
            </form>

            <?php if ($workers_result && count($workers_result) > 0): ?>
                <div class="worker">
                    <h3>Available Workers:</h3>
                    <?php foreach ($workers_result as $worker): ?>
                        <div class="worker-item">
                            <p><strong><?php echo htmlspecialchars($worker['name']); ?></strong> - <?php echo htmlspecialchars($worker['specialty']); ?> (Rate: <?php echo htmlspecialchars($worker['hourly_rate']); ?>)</p>
                            <form method="POST">
                                <input type="hidden" name="worker_id" value="<?php echo $worker['worker_id']; ?>">
                                <textarea name="Description" placeholder="Describe the task" required></textarea>
                                <button type="submit">Book This Worker</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($message): ?>
                <p class="message"> <?php echo htmlspecialchars($message); ?> </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
