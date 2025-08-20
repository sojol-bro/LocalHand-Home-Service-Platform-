<?php
// help_support.php

// Sample help and support materials
$helpMaterials = [
    [
        'title' => 'Getting Started',
        'description' => 'A guide to help you get started with our services.',
        'link' => 'getting_started.php'
    ],
    [
        'title' => 'FAQ',
        'description' => 'Frequently Asked Questions about our services.',
        'link' => 'faq.php'
    ],
    [
        'title' => 'Contact Support',
        'description' => 'Reach out to our support team for assistance.',
        'link' => 'contact_support.php'
    ],
    [
        'title' => 'User Manual',
        'description' => 'Comprehensive user manual for our application.',
        'link' => 'user_manual.php'
    ],
    [
        'title' => 'Troubleshooting Guide',
        'description' => 'Common issues and how to resolve them.',
        'link' => 'troubleshooting.php'
    ],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support</title>
    <link rel="stylesheet" href="styles2.css"> <!-- Link to your CSS file -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        header {
            width: 100%;
            background-color: #467187;
            padding: 20px 0;
            text-align: center;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 600;
        }

        .back-button {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            text-decoration: none;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .back-button:hover {
            color: #dcdcdc;
        }

        main {
            width: 80%;
            max-width: 1200px;
            margin: 30px 0;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            font-size: 1.8rem;
            color: #467187;
            font-weight: 600;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            display: flex;
            flex-direction: column;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        ul li:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        ul li h3 {
            font-size: 1.4rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }

        ul li p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 15px;
        }

        a {
            text-decoration: none;
            color: #467187;
            font-weight: 600;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #3c5c66;
        }

        footer {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-top: 30px;
        }

        footer p {
            font-size: 1rem;
            font-weight: 400;
        }
    </style>
</head>
<body>
    <header>
        <a href="javascript:history.back()" class="back-button">&larr; Back</a>
        <h1>Help & Support</h1>
    </header>

    <main>
        <section>
            <h2>Available Resources</h2>
            <ul>
                <?php foreach ($helpMaterials as $material): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($material['title']); ?></h3>
                        <p><?php echo htmlspecialchars($material['description']); ?></p>
                        <a href="<?php echo htmlspecialchars($material['link']); ?>">Learn More</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Local-Hand. All rights reserved.</p>
    </footer>
</body>
</html>
