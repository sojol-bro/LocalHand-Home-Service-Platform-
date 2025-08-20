<?php
// Start session and include database connection
session_start();
include('db.php');

// Get the worker_id and product_name from the URL parameters
$worker_id = isset($_GET['worker_id']) ? $_GET['worker_id'] : null;
$product_name = isset($_GET['product_name']) ? $_GET['product_name'] : null;



// Fetch worker details
$workerQuery = "SELECT * FROM workers WHERE worker_id = :worker_id";
$stmt = $pdo->prepare($workerQuery);
$stmt->execute(['worker_id' => $worker_id]);
$worker1 = $stmt->fetch(PDO::FETCH_ASSOC);

if ($worker_id && $product_name) {
    try {
        // Fetch product details from the market_products table
        $sql = "SELECT * FROM market_products WHERE worker_id = :worker_id AND product_name = :product_name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':worker_id', $worker_id);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            // Fetch worker's details from the workers table
            $worker_sql = "SELECT phone, email FROM workers WHERE worker_id = :worker_id";
            $worker_stmt = $pdo->prepare($worker_sql);
            $worker_stmt->bindParam(':worker_id', $worker_id);
            $worker_stmt->execute();
            $worker = $worker_stmt->fetch(PDO::FETCH_ASSOC);
            
            // Display product details
            $product_image = htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8');
            $product_name = htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8');
            $product_stock = $product['stock'];
            $product_price = number_format($product['price'], 2);
            $worker_phone = htmlspecialchars($worker['phone'], ENT_QUOTES, 'UTF-8');
            $worker_email = htmlspecialchars($worker['email'], ENT_QUOTES, 'UTF-8');
        } else {
            echo "<p>Product not found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Invalid product or worker information.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #E1EACD;
        }

        .navbar {
            position: sticky;
            top: 0;
            background-color: rgb(108, 125, 142);
            padding: 10px 20px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
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
            padding: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .product-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .product-details img {
            max-width: 400px;
            height: auto;
            border-radius: 5px;
        }

        .product-info {
            max-width: 500px;
        }

        .product-info h2 {
            margin-top: 0;
        }

        .worker-info {
            margin-top: 20px;
        }

        .worker-info p {
            margin: 5px 0;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 1.1em;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Message icon position */
        .message-icon {
            position: absolute;
            top: 60px;
            right: 50px;
            font-size: 24px; /* Size of the icon */
            color: rgb(52, 109, 170); /* Icon color */
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .message-icon a {
            color: inherit; /* Ensures the link inherits the color of the icon */
        }

        .message-icon:hover {
            color: #0056b3; /* Change color on hover */
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo"></div>
    <div class="links">
        <a href="javascript:history.back();">Back</a>
    </div>
</div>

<!-- Message Icon -->
<div class="message-icon">
    <a href="conversation.php?worker_id=<?= $worker1['worker_id']; ?>" >
        <i class="fas fa-comment-dots"></i> <!-- FontAwesome message icon -->
    </a>
</div>

<div class="container">
    <?php if (isset($product)): ?>
        <div class="product-details">
            <img src="<?php echo $product_image; ?>" alt="Product Image">
            <div class="product-info">
                <h2><?php echo $product_name; ?></h2>
                <p><strong>Price:</strong> ৳ <?php echo $product_price; ?></p>
                <p><strong>Stock:</strong> <?php echo $product_stock; ?> items available</p>
            </div>
        </div>

        <div class="worker-info">
            <h3>Seller Details:</h3>
            <p><strong>Phone:</strong> <?php echo $worker_phone; ?></p>
            <p><strong>Email:</strong> <a href="mailto:<?php echo $worker_email; ?>"><?php echo $worker_email; ?></a></p>
        </div>

        <a href="market.php" class="back-link">Back to Marketplace</a>
    <?php endif; ?>
</div>

</body>
</html>
