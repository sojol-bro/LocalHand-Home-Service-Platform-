<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Reset and Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f8fc;
            color: #333;
        }

        /* Navbar Styles */
        .navbar {
            position: sticky;
            top: 0;
            background-color: #6c7d8e;
            padding: 15px 20px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .links a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar .links a:hover {
            color: #d1ecf1;
        }

        /* Container Styles */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Product Card */
        .product {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product img {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 5px;
        }

        .product h3 {
            font-size: 1.2rem;
            margin: 15px 0;
            color: #333;
        }

        .product p {
            margin: 5px 0;
            color: #555;
        }

        .product .price {
            color: #28a745;
            font-weight: bold;
            font-size: 1rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar {
                flex-wrap: wrap;
            }

            .navbar .links a {
                margin: 0 10px;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">Marketplace</div>
    <div class="links">
        <a href="worker_dashboard.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="my_products.php">My Products</a>
    </div>
</div>

<div class="container">
    <div class="product-grid">
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
</div>

</body>
</html>
