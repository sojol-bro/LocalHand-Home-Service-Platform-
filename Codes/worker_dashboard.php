<?php
// Include the database connection file
include 'db.php';

// Start the session to get the worker_id (assuming worker_id is stored in the session)
session_start();
if (!isset($_SESSION['worker_id'])) {
    die("Worker not logged in.");
}
$worker_id = $_SESSION['worker_id'];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    // Update worker status in the database
    $sql = "UPDATE workers SET status = ? WHERE worker_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $status, PDO::PARAM_STR);
    $stmt->bindParam(2, $worker_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating status.');</script>";
    }
    $stmt->closeCursor();
}

// Fetch worker status from the database
$sql = "SELECT status FROM workers WHERE worker_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $worker_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$status = $result ? $result['status'] : 'active';
$stmt->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

       body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            background-image: url(image/workerBg.jpg);
            background-size: cover; /* Ensures the image covers the entire background */
            background-position: top; /* Centers the image */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            font-family: 'Arial', sans-serif;
            color: #333;
        }


        /* Navbar Styles */
        .navbar {
            background-color: #34495e;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar a {
            color: #fff !important;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
            margin: 0 15px;
        }

        .navbar a:hover {
            color: #1abc9c !important;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 24px;
            color: #ecf0f1 !important;
        }

        .dropdown-menu {
            background-color: #ecf0f1;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu a {
            color: #2c3e50 !important;
            padding: 10px 15px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .dropdown-menu a:hover {
            background-color: #bdc3c7;
        }

        /* Main Content */
        #main-content {
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

       #main-content h2 {
            font-size: 28px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 20px;
            text-align: center; /* Center-align the text */
        }


        /* Status Form */
        .form-check {
            margin: 15px 0;
        }

        .form-check-input {
            accent-color: #1abc9c;
        }

        .form-check-label {
            font-size: 16px;
            color: #2c3e50;
        }

        /* Message Icon */
        .message-icon {
            font-size: 24px;
            color: #fff;
            position: relative;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .message-icon:hover {
            transform: scale(1.1);
            color: #1abc9c;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
            }

            .dropdown-menu {
                width: 100%;
            }

            #main-content {
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="worker_dashboard.php">Local Hand</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="market_worker.php">Market Place</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="jobDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Job
                        </a>
                        <div class="dropdown-menu" aria-labelledby="jobDropdown">
                            <a class="dropdown-item" href="job_request.php">Job Requests</a>
                            <a class="dropdown-item" href="previous_work.php">Previous Works</a>
                            <a class="dropdown-item" href="worker_feedback.php">Feedbacks</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="worker_profile.php">Manage Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="h&p.php">Help & Support</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="statusDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Status
                        </a>
                        <div class="dropdown-menu" aria-labelledby="statusDropdown">
                            <form method="POST" action="" id="statusForm">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="active" value="active" <?php echo ($status === 'active') ? 'checked' : ''; ?> onchange="document.getElementById('statusForm').submit();">
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="inactive" value="inactive" <?php echo ($status === 'inactive') ? 'checked' : ''; ?> onchange="document.getElementById('statusForm').submit();">
                                    <label class="form-check-label" for="inactive">Inactive</label>
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link message-icon" href="worker_messages.php">
                            <i class="fas fa-envelope"></i> Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="main-content">
        <h2>Welcome to Local Hand</h2>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
