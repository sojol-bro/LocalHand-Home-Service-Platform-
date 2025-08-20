<?php
include('db.php');

// Get search and location parameters from the URL
$search = isset($_GET['search']) ? $_GET['search'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Workers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: rgb(235, 235, 250);
            text-align: center;
        }
        .navbar {
            position: sticky;
            top: 0;
            background: rgb(43, 105, 105);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .main-content {
            padding: 20px;
        }
        .worker-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 10px auto;
            max-width: 600px;
            text-align: left;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .worker-card h3 {
            margin: 0;
            color: rgb(43, 105, 105);
        }
        .worker-card p {
            margin: 5px 0;
        }
        .worker-card a {
            text-decoration: none;
            color: white;
            background: rgb(100, 125, 178);
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .worker-card a:hover {
            background: rgb(43, 105, 105);
        }
        .no-results {
            margin-top: 20px;
            color: red;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="user_dashboard.php">Dashboard</a>
            <a href="service.php">Services</a>
            <a href="market.php">Market Place</a>
          
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2>Search Results</h2>

        <?php
        // Query the workers table based on the search inputs
        $query = "SELECT * FROM workers WHERE specialty LIKE :search";
        if (!empty($location)) {
            $query .= " AND address = :location";
        }

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        if (!empty($location)) {
            $stmt->bindValue(':location', $location, PDO::PARAM_STR);
        }

        $stmt->execute();
        $workers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display results
        if ($workers) {
            foreach ($workers as $worker) {
                echo "
                <div class='worker-card'>
                    <h3>" . htmlspecialchars($worker['name'], ENT_QUOTES, 'UTF-8') . "</h3>
                    <p><strong>Specialty:</strong> " . htmlspecialchars($worker['specialty'], ENT_QUOTES, 'UTF-8') . "</p>
                    <p><strong>Address:</strong> " . htmlspecialchars($worker['address'], ENT_QUOTES, 'UTF-8') . "</p>
                    
                    <a href='worker_profile_view.php?worker_id=" . htmlspecialchars($worker['worker_id'], ENT_QUOTES, 'UTF-8') . "'>View Details</a>
                </div>";
            }
        } else {
            echo "<div class='no-results'>No workers found matching your criteria.</div>";
        }
        ?>
    </div>
</body>
</html>
