<?php
include 'db.php';
session_start();
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            padding-top: 20px;
        }
        .navbar {
            position: sticky;
            top: 0;
            padding: 15px 25px;
            background-color: rgba(74, 149, 167, 0.8);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color:rgb(47, 71, 204);
        }


        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
            font-size: 2.5rem;
        }

        /* Main Section */
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .card {
            background-color: #fff;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
            text-align: center;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .card h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1rem;
            color: #555;
        }

        .button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
        }

        .button:hover {
            background-color: #0056b3;
        }

        /* Footer */
        footer {
            margin-top: 40px;
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: #fff;
            font-size: 0.9rem;
        }

        footer a {
            color: #1e90ff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="navbar">
        <div>
            
        </div>
        <a href="logout.php">Logout</a>
    </div>
    <!-- Main Content -->
    <div class="content">
        <h1>Welcome, Admin</h1>

        <div class="card">
            <h2>Manage Workers</h2>
            <p>Approve or reject new worker applications with ease.</p>
            <a href="worker_approval.php" class="button">Work Approval</a>
        </div>

        <div class="card">
            <h2>Handle Reviews</h2>
            <p>Moderate reviews and ensure quality feedback on your platform.</p>
            <a href="review_handle.php" class="button">Review Handle</a>
        </div>

        <div class="card">
            <h2>Set Categories</h2>
            <p>Organize services into categories to streamline user navigation.</p>
            <a href="category_set.php" class="button">Set Categories</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date('Y'); ?> Admin Dashboard. Built with care.
    </footer>

</body>
</html>
