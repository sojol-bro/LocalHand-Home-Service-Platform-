<?php
session_start();
include('db.php');

// Get the category_id from the URL (query string)
$category_id = isset($_GET['p_category_id']) ? (int)$_GET['p_category_id'] : 0;

try {
    if ($category_id > 0) {
        // Fetch the selected category name based on category_id
        $sql = "SELECT category_name FROM product_categories WHERE p_category_id = :p_category_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':p_category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the category exists
        if (!$category) {
            throw new Exception("Category not found.");
        }

        // Fetch products for the selected category
        $sql = "SELECT product_id, product_name, price, image, worker_id 
                FROM market_products 
                WHERE p_category_id = :p_category_id AND stock > 0";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':p_category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        throw new Exception("Invalid category ID.");
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products in Category</title>
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
            background-color: #343a40;
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

        .navbar .back-button {
            position: absolute;
            left: 20px;
            font-size: 1.2em;
            color: #fff;
        }

        .navbar .back-button:hover {
            text-decoration: underline;
        }

        .navbar .home-link {
            margin-left: auto;
        }

        .main-content {
            padding: 20px;
            margin-top: 60px;
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
            transition: box-shadow 0.3s;
        }

        .product:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

        .product a {
            text-decoration: none;
            color: inherit;
        }

        .product a:hover {
            color: #007bff;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="back-button">
            <a href="javascript:history.back();">&lt; back</a>
        </div>
        <a href="index.php" class="home-link">Home</a>
    </div>

    <div class="main-content">
        <?php if (isset($error_message)) { ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } else { ?>
            <h1><?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?></h1>

            <?php if (!empty($products)) { ?>
                <div class="products">
                    <?php foreach ($products as $product) { ?>
                        <a href="product_details.php?product_name=<?php echo urlencode($product['product_name']); ?>&worker_id=<?php echo urlencode($product['worker_id']); ?>" class="product-link">
    <div class="product">
        <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Product Image">
        <h3><?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
        <p class="price">৳ <?php echo number_format($product['price'], 2); ?></p>
    </div>
</a>

                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>No products available in this category.</p>
            <?php } ?>
        <?php } ?>
    </div>
</body>
</html>
