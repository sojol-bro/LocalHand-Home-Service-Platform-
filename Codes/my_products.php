<?php
session_start();
include('db.php');

// Ensure the worker is logged in by checking their session
$worker_id = isset($_SESSION['worker_id']) ? $_SESSION['worker_id'] : null;

if (!$worker_id) {
    echo "Access restricted to workers only. Please log in as a worker.";
    exit;
}

try {
    // Fetch the products for the logged-in worker
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
    <title>My Products</title>
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

        .content {
            display: flex;
        }

        .sidebar {
            position: sticky;
            top: 0;
            width: 120%;
            background-color:rgb(84, 125, 166);
            color: #fff;
            height: 100vh;
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 10px;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1em;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
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

        .product .actions {
            text-align: right;
        }

        .actions button {
            background: transparent;
            border: none;
            color: #28a745;
            cursor: pointer;
        }

        .actions button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <a href="market.php">Marketplace</a>
        </div>
        <div class="links">
            <a href="worker_dashboard.php">Home</a>
           
        </div>
    </div>

    <div class="content">
        <div class="sidebar">
            <h2>MY Products</h2>
            <ul>
                <li><a href="add_product.php">Add Product</a></li>
               <!-- <li><a href="update.php">Update Product</a></li> -->
            </ul>
        </div>

        <div class="main-content">
            <h1>Your Products</h1>

            <?php if (!empty($products)) { ?>
    <div class="products">
        <?php
        foreach ($products as $product) {
            $imagePath = !empty($product['image']) ? htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') : 'default_image.jpg';
            $productId = htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8');
            
            echo "<div class='product' id='product-$productId'>";
            echo "<img src='" . $imagePath . "' alt='Product Image'>";
            echo "<h3>" . htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') . "</h3>";
            echo "<p class='price'>৳ " . number_format($product['price'], 2) . "</p>";
            echo "<div class='actions'>
                    <button onclick='deleteProduct($productId)' class='btn btn-danger btn-sm'>Delete</button>
                    <form method='GET' action='update.php' style='display:inline-block;'>
                        <input type='hidden' name='product_id' value='$productId'>
                        <button type='submit' class='btn btn-primary btn-sm'>Update</button>
                    </form>
                  </div>";
            echo "</div>";
        }
        ?>
    </div>
<?php } else { ?>
    <p>No products available.</p>
<?php } ?>

<script>
    function deleteProduct(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            fetch('delete_product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product deleted successfully!');
                    document.getElementById(`product-${productId}`).remove();
                } else {
                    alert('Failed to delete product: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the product.');
            });
        }
    }
</script>

        </div>
    </div>
</body>
</html>
