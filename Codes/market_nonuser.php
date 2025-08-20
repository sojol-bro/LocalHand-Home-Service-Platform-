<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        }

        .product {
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            padding: 15px;
            margin: 10px;
            text-align: center;
            width: 250px;
            display: inline-block;
            vertical-align: top;
        }

        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product h3 {
            font-size: 1.2em;
            margin: 15px 0;
        }

        .product p {
            margin: 5px 0;
            color: #6c757d;
        }

        .product .price {
            color: #28a745;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo"></div>
    <div class="links">
        <a href="index.php">Home</a>
        <a href="categories_nonuser.php">Categories</a>
        <a href="my_products.php">My Products</a>
    </div>
</div>

<div class="container">
    <?php
    session_start();
    include('db.php');

    try {
        // Fetch products from market_products table
        $sql = "SELECT product_id, product_name, price, image, worker_id FROM market_products WHERE stock > 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Output each product
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product_name = htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8');
                $product_price = number_format($row['price'], 2);
                $product_image = htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8');
                $worker_id = $row['worker_id'];

                // Create a link to product_details.php with worker_id and product_name as URL parameters
                $product_url = "product_details_nonuser.php?worker_id=" . $worker_id . "&product_name=" . urlencode($product_name);

                echo "<div class='product'>";
                echo "<a href='" . $product_url . "'>";
                echo "<img src='" . $product_image . "' alt='Product Image'>";
                echo "<h3>" . $product_name . "</h3>";
                echo "<p class='price'>৳ " . $product_price . "</p>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No products available.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</div>

</body>
</html>
