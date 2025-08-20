<?php
session_start();
include('db.php');

// Ensure only workers can access this page
$worker_id = isset($_SESSION['worker_id']) ? $_SESSION['worker_id'] : null;

if (!$worker_id) {
    echo "Only workers can access this page. Please log in as a worker.";
    exit;
}

// Fetch product categories for the dropdown
try {
    $sql = "SELECT * FROM product_categories";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $category_id = $_POST['p_category_id'] ?? 0;
    $image = '';

    // Validate category exists
    $category_exists = false;
    try {
        $sql = "SELECT COUNT(*) FROM product_categories WHERE p_category_id = :p_category_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':p_category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $category_exists = $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    if (!$category_exists) {
        $error_message = "Invalid category selected.";
    } else {
        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = 'uploads/' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        }

        // Insert product into the database
        try {
            $sql = "INSERT INTO market_products (p_category_id, worker_id, product_name, price, stock, image) 
                    VALUES (:p_category_id, :worker_id, :product_name, :price, :stock, :image)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
            $stmt->bindParam(':p_category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':worker_id', $worker_id, PDO::PARAM_INT);
            $stmt->execute();
            $success_message = "Product added successfully!";
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Fetch existing products for the logged-in worker
try {
    $sql = "SELECT product_id, product_name, price, stock, image FROM market_products WHERE worker_id = :worker_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':worker_id', $worker_id, PDO::PARAM_INT);
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
    <title>Worker's Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Styles for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f9;
            color: #333;
        }

        .navbar {
            position: sticky;
            top: 0;
            background-color:rgb(76, 139, 184);
            padding: 10px 20px;
            color:rgb(39, 40, 41);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .navbar a {
            color:rgb(5, 18, 34);
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
            width: 250px;
            background-color:rgb(74, 108, 164);
            color: #fff;
            height: 100vh;
            padding: 20px;
            position: sticky;
            top: 0;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.2em;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #495057;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-container h3 {
            margin-bottom: 10px;
        }

        .form-container input, .form-container select, .form-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container button {
            background-color:rgb(116, 148, 183);
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color:rgb(115, 169, 226);
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            <a href="#">Marketplace</a>
        </div>
        <div class="links">
            <a href="market.php">Market</a>
            <a href="my_products.php">My Products</a>
        </div>
    </div>

    <div class="content">
        <div class="sidebar">
            <h2>Product Management</h2>
            <ul>
                <li><a href="worker_dashboard.php">Home</a></li>
              
            </ul>
        </div>

        <div class="main-content">
            <h1>Add a New Product</h1>
            <?php if (isset($success_message)) echo "<p style='color:green;'>$success_message</p>"; ?>
            <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>

            <div class="form-container">
                <h3>Product Details</h3>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="text" name="product_name" placeholder="Product Name" required>
                    <input type="number" name="price" step="0.01" placeholder="Price (৳)" required>
                    <input type="number" name="stock" placeholder="Stock" required>
                    <select name="p_category_id" required>
                        <option value="" disabled selected>Select Category</option>
                        <?php
                        if (!empty($categories)) {
                            foreach ($categories as $category) {
                                echo "<option value='" . $category['p_category_id'] . "'>" . htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                        }
                        ?>
                    </select>

                    <input type="file" name="image" accept="image/*">
                    <button type="submit">Add Product</button>
                </form>
            </div>

            <h1>Your Products</h1>
            <div class="products">
                <?php if (!empty($products)) {
                    foreach ($products as $product) {
                        $imagePath = !empty($product['image']) ? htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') : 'default_image.jpg';
                        echo "<div class='product'>";
                        echo "<img src='" . $imagePath . "' alt='Product Image'>";
                        echo "<h3>" . htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') . "</h3>";
                        echo "<p class='price'>৳ " . number_format($product['price'], 2) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No products available.</p>";
                } ?>
            </div>
        </div>
    </div>
</body>
</html>
