<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        a:hover {
            color: #007bff;
        }

        /* Navbar */
        .navbar {
            position: sticky;
            top: 0;
            background-color: #46718797;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .links a {
            margin: 0 15px;
            font-size: 1rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar .links a:hover {
            color: #000000;
        }

        /* Container */
        .container {
            padding: 40px 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Product Card */
        .product {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
            margin: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product h3 {
            font-size: 1.25rem;
            margin: 15px;
            color: #333;
        }

        .product .price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #28a745;
            margin: 0 15px 15px;
        }

        .product p {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0 15px 15px;
        }

        .product a {
            display: block;
            color: inherit;
            text-align: center;
            padding: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar .links a {
                margin: 0 10px;
                font-size: 0.9rem;
            }

            .product {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo"><strong>Marketplace</strong></div>
    <div class="links">
        <a href="javascript:history.back()" class="back-btn">&larr; Back</a>
        <a href="categories.php">Categories</a>
        <!-- <a href="my_products.php">My Products</a> -->
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
                $product_url = "product_details.php?worker_id=" . $worker_id . "&product_name=" . urlencode($product_name);

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
