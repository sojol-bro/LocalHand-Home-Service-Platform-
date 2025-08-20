<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', Arial, sans-serif;
            height: 100vh;
            background: linear-gradient(rgba(171, 175, 177, 0.5), rgba(0, 0, 0, 0.5)), url('image/userdeshBoard.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
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

        .main-content {
            text-align: center;
            padding: 60px 20px;
        }

        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin: 40px auto;
            flex-wrap: wrap;
        }

        .location-dropdown, .search-bar {
            padding: 12px;
            
            border: none;
            font-size: 1rem;
            outline: none;
            width: 250px;
        }

        .location-dropdown {
            background-color: #fff;
            color: #333;
        }

        .search-bar {
            background-color: #f8f9fa;
            color: #333;
        }

        .search-btn {
            padding: 12px 25px;
            background: #6c5ce7;
            border: none;
            
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .search-btn:hover {
            background: #5a4de0;
        }

        h2 {
            font-size: 3rem;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.7);
            margin: 100px 0 20px;
        }

        @media (max-width: 768px) {
            .navbar a {
                font-size: 0.9rem;
                margin: 0 10px;
            }

            .search-container {
                flex-direction: column;
            }

            .location-dropdown, .search-bar {
                width: 90%;
            }

            h2 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="service.php">Services</a>
            <a href="market.php">Market Place</a>
            <a href="book.php">Bookings</a>
            <a href="reviews.php">Feedback</a>
            <a href="user_profile.php">Profile</a>
            <a href="h&p.php">Help & Support</a>
            <a href="user_messages.php" class="dashboard-item">
                <i class="fas fa-envelope"></i> Messages
            </a>
        </div>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h2>Welcome to Local Hand</h2>
        <form method="GET" action="search_worker.php" class="search-container">
            <select name="location" class="location-dropdown">
                <option value="">Select Location</option>
                <?php
                // Fetch unique addresses (locations) from workers table
                $stmt = $pdo->query("SELECT DISTINCT address FROM workers WHERE address IS NOT NULL");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
            <input type="text" name="search" class="search-bar" placeholder="Find your service here...">
            <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
        </form>
    </div>
</body>
</html>
