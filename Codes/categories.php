<?php
session_start();
include('db.php');

// Initialize category_id to 0, as we will fetch all products
$category_id = 0;

try {
    // Fetch all categories
    $sql = "SELECT p_category_id, category_name FROM product_categories";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all products (no category filter)
    $sql = "SELECT product_name, price, image, p_category_id, worker_id FROM market_products WHERE stock > 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products in Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .navbar {
            position: sticky;
            top: 0;
            background-color:rgb(21, 83, 145);
            padding: 10px 20px;
            color: rgb(36, 68, 100);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .navbar a {
            color: rgb(215, 226, 235);
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .content {
            display: flex;
        }

        .sidebar {
            position: sticky;
            top: 0;
            width: 130%;
            max-width: 600px;
            background-color:rgb(151, 187, 224);
            color: rgb(2, 24, 46);
            height: 100vh;
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: rgb(9, 33, 57);
            text-decoration: none;
            font-size: 1em;
            display: block;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #495057;
            text-decoration: none;
        }

        .main-content {
            flex-grow: 1;
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
            cursor: pointer;
        }

        .product a {
            text-decoration: none;
            color: inherit;
        }

        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product h3 {
            font-size: 1.2em;
            margin: 10px 0;
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
        <div class="logo">
          <!--  <a href="#">Marketplace</a> -->
        </div>
        <div class="links">
            <a href="javascript:history.back();">Back</a>
            <a href="my_products.php">My Products</a>
        </div>
    </div>

    <div class="content">
        <div class="sidebar">
            <h2>Categories</h2>
            <ul>
                <?php
                $activeCategoryId = $_GET['p_category_id'] ?? null;

                // Display all categories in the sidebar
                foreach ($categories as $category) {
                    $isActive = ($category['p_category_id'] == $activeCategoryId) ? 'active' : '';
                    echo "<li><a href='p_categories.php?p_category_id=" . htmlspecialchars($category['p_category_id'], ENT_QUOTES, 'UTF-8') . "' class='$isActive'>" . htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8') . "</a></li>";
                }
                ?>
            </ul>
        </div>

        <div class="main-content">
            <h1>All Products</h1>
            
            <?php if (!empty($products)) { ?>
                <div class="products">
                    <?php
                    foreach ($products as $product) {
                        $productName = htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8');
                        $workerId = htmlspecialchars($product['worker_id'], ENT_QUOTES, 'UTF-8');
                        $productUrl = "product_details.php?product_name=$productName&worker_id=$workerId";
                        echo "<div class='product'>";
                        echo "<a href='$productUrl'>";
                        echo "<img src='" . htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') . "' alt='Product Image'>";
                        echo "<h3>$productName</h3>";
                        echo "<p class='price'>৳ " . number_format($product['price'], 2) . "</p>";
                        echo "</a>";
                        echo "</div>";
                    }
                    ?>
                </div>
            <?php } else { ?>
                <p>No products available.</p>
            <?php } ?>
        </div>
    </div>
</body>
</html>
