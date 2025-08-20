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

// Fetch the product to update
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if ($product_id) {
    try {
        $sql = "SELECT * FROM market_products WHERE product_id = :product_id AND worker_id = :worker_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':worker_id', $worker_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (!$product) {
    echo "Product not found or you don't have permission to edit it.";
    exit;
}

// Handle product update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $category_id = $_POST['p_category_id'] ?? 0;
    $image = $product['image']; // Retain the existing image if no new image is uploaded

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
        // Handle file upload (if a new image is provided)
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = 'uploads/' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        }

        // Update the product in the database
        try {
            $sql = "UPDATE market_products 
                    SET product_name = :product_name, price = :price, stock = :stock, 
                        image = :image, p_category_id = :p_category_id
                    WHERE product_id = :product_id AND worker_id = :worker_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
            $stmt->bindParam(':p_category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':worker_id', $worker_id, PDO::PARAM_INT);
            $stmt->execute();
            $success_message = "Product updated successfully!";
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #4c8bb5;
            padding: 15px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .content {
            display: flex;
            padding: 20px;
        }

        .sidebar {
            width: 250px;
            background-color: #4a6ca4;
            color: white;
            padding: 20px;
            height: 100vh;
            position: sticky;
            top: 0;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
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
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-container h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .form-container input, .form-container select, .form-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container button {
            background-color: #74a8b7;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #759be2;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .product {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 250px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product h3 {
            font-size: 1.2em;
            margin-top: 10px;
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
            <a href="market_worker.php">Home</a>
            <a href="my_products.php">My Products</a>
        </div>
    </div>

    <div class="content">
        <div class="sidebar">
            <h2>Product Management</h2>
            <ul>
                <li><a href="#">Home</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1>Update Product</h1>
            <?php if (isset($success_message)) echo "<div class='success-message'>$success_message</div>"; ?>
            <?php if (isset($error_message)) echo "<div class='error-message'>$error_message</div>"; ?>

            <div class="form-container">
                <h3>Product Details</h3>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    <select name="p_category_id" required>
                        <option value="" disabled>Select Category</option>
                        <?php
                        if (!empty($categories)) {
                            foreach ($categories as $category) {
                                $selected = ($category['p_category_id'] == $product['p_category_id']) ? 'selected' : '';
                                echo "<option value='" . $category['p_category_id'] . "' $selected>" . htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                        }
                        ?>
                    </select>

                    <input type="file" name="image" accept="image/*">
                    <button type="submit">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
