<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your booking history.";
    exit;
}

// Fetch the user's booking history
$user_id = $_SESSION['user_id'];
$bookings_query = "SELECT b.booking_id, b.booking_date, b.status, b.Description, w.name AS worker_name, w.specialty 
                   FROM bookings b
                   JOIN workers w ON b.worker_id = w.worker_id
                   WHERE b.user_id = :user_id
                   ORDER BY b.booking_date DESC";

$stmt = $pdo->prepare($bookings_query);
$stmt->execute([':user_id' => $user_id]);
$bookings_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Booking History</title>
    <style>
        body {
            background-color: rgb(167, 179, 244);
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Sticky Navbar */
        .navbar {
            position: sticky;
            top: 0;
            background-color: rgb(108, 105, 180);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .navbar .back-btn {
            font-size: 20px;
            cursor: pointer;
        }

        .navbar .dashboard-link {
            font-size: 18px;
        }

        .booking-history {
            margin: 330px;
            padding: 20px;
            background-color: rgb(162, 147, 230);
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 800px;
            margin-top: 80px; /* Add space below navbar */
            overflow-y: auto;
        }

        .booking-history h2 {
            font-size: 28px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: rgb(108, 105, 180);
            color: white;
        }

        .message {
            color: green;
            font-size: 18px;
        }

    </style>
</head>
<body>
    <!-- Sticky Navbar -->
    <div class="navbar">
        <a href="javascript:history.back()" class="back-btn">&#8592; Back</a>
        <a href="user_dashboard.php" class="dashboard-link">Dashboard</a>
    </div>

    <!-- Booking History -->
    <div class="booking-history">
        <h2>Your Booking History</h2>
        
        <?php if (count($bookings_result) > 0): ?>
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>Worker</th>
                    <th>Specialty</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Description</th>
                </tr>
                <?php foreach ($bookings_result as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['worker_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['specialty']); ?></td>
                        <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                        <td><?php echo htmlspecialchars($booking['Description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="message">You have no booking history.</p>
        <?php endif; ?>
    </div>
</body>
</html>
