<?php
include 'db.php';  // Database connection

// Fetch categories from work_categories table
$categoryQuery = "SELECT * FROM work_categories";
$categories = $pdo->query($categoryQuery)->fetchAll(PDO::FETCH_ASSOC);

// Fetch specialties grouped by category
$specialtyQuery = "SELECT * FROM specialties";
$specialtyResult = $pdo->query($specialtyQuery);

$specialties = [];
foreach ($specialtyResult as $row) {
    $specialties[$row['category_id']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #467187, #467187);
            color: white;
            position: fixed;
            height: 100%;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
        }

        .sidebar h3 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        .content h2 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #212529;
        }

        .service-section {
            margin-bottom: 50px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .service-section h3 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #495057;
        }

        .service-images {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .service-item {
            width: 200px;
            text-align: center;
            border: 1px solid #467187;
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .service-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .service-item span {
            font-size: 18px;
            font-weight: 600;
            color: #212529;
        }

        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #467187;
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background-color:#495057;
        }

        button {
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background: #495057;
            transform: scale(1.05);
        }
    </style>

    <script>
        $(document).ready(function () {
            $('.category-link').click(function (e) {
                e.preventDefault();
                const categoryId = $(this).data('category');

                $('html, body').animate({
                    scrollTop: $(`.service-section[data-category="${categoryId}"]`).offset().top - 20
                }, 800);
            });
        });
    </script>
</head>
<body>
    <div class="sidebar">
        <h3>Service Categories</h3>
        <?php foreach ($categories as $category) : ?>
            <a href="#" class="category-link" data-category="<?= $category['category_id'] ?>">
                <?= htmlspecialchars($category['category_name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="content">
        <h2>Available Services</h2>
        <?php foreach ($specialties as $categoryId => $specialtyList) : ?>
            <div class="service-section" data-category="<?= $categoryId ?>">
                <h3>
                    <?php
                    $category = array_filter($categories, function ($cat) use ($categoryId) {
                        return $cat['category_id'] == $categoryId;
                    });
                    $category = reset($category);
                    echo $category ? htmlspecialchars($category['category_name']) : "Unknown Category";
                    ?>
                </h3>
                <div class="service-images">
                    <?php foreach ($specialtyList as $specialty) : ?>
                        <div class="service-item">
                            <a href="worker_service.php?specialty_id=<?= htmlspecialchars($specialty['specialty_id']) ?>">
                                <img src="<?= htmlspecialchars($specialty['image']) ?>" 
                                     onerror="this.onerror=null; this.src='images/default.jpg'" 
                                     alt="Service Image" />
                                <span><?= htmlspecialchars($specialty['specialty_name']) ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <a href="javascript:history.back()" class="back-btn">&larr; Back</a>
    </div>
</body>
</html>
