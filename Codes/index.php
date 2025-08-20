<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Finder Dashboard</title>
    <style>
       body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: rgb(134, 134, 138);
            background-image: url(image/indexBg.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
            text-align: center;
        }

        /* nav */
        section {
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            padding: 15px 25px; 
            background-color: #46718797; 
        }

        section .logo img {
            height: 40px;
            display: block; 
        }

        section .navbar {
            display: flex; 
            gap: 55px; 
        }

        section .navbar a {
            color: #ffffff; 
            text-decoration: none; 
            font-size: 16px; 
            font-weight: bold;
            transition: background-color 0.3s ease; 
        }

        section .navbar a:hover {
            color: #000000; 
        }
        
        .navbar a[href="index.php"] {
            color: #000000; 
            font-weight: bold;
        }
        /* nav end */



        h2 {
            font-size: 36px; 
            font-weight: bold; 
            color: #ffffff; 
            text-align: center; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); 
            margin: 40px 0; 
        }


        .second_h2 {
            font-size: 45px; 
            font-weight: bold; 
            color: #212f3c; 
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 5px rgba(231, 230, 230, 0.56);
            margin: 120px 0px 10px 0px;
        }

        h6 {
            font-size: 15px;
            font-weight: bold;
            color: #ffffff;
            text-align: center; 
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); 
            margin: 10px 0; 
        }


        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0; 
        }

        .search-bar {
            width: 300px;
            padding: 10px 15px; 
            border: 2px solid #467187;
            border-radius: 8px; 
            font-size: 16px; 
            outline: none; 
            transition: all 0.3s ease; 
        }

        .search-bar:focus {
            border-color: #0056b3; 
            box-shadow: 0 0 8px rgba(0, 86, 179, 0.5); 
        }

        button {
            margin-left: 10px; 
            padding: 10px 20px; 
            background-color: #46718797;
            color: #ffffff; 
            border: none; 
            border-radius: 8px; 
            font-size: 16px;
            font-weight: bold; 
            cursor: pointer; 
            transition: all 0.3s ease; 
        }

        button:hover {
            background-color: #000000; 
            box-shadow: 0 0 10px rgba(0, 86, 179, 0.5); 
        }


        footer.footer {
            background-color: #46718797; 
            color: #ffffff;
            margin-top:380px;
            padding: 20px 10% ; 
            text-align: center; 
            font-family: Arial, sans-serif; 
        }

        footer .footer-content {
            margin-bottom: 15px;
        }

        footer .footer-content p {
            margin: 5px 0; 
            font-size: 14px; 
        }

        footer .footer-links {
            margin: 10px 0; 
        }

        footer .footer-links a {
            color: #ffffff; 
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px; 
            transition: color 0.3s ease;
        }

        footer .footer-links a:hover {
            color: #000000; 
        }

        footer .social-media {
            margin: 10px 0; 
        }

        footer .social-media a {
            color: #ffffff; 
            text-decoration: none; 
            margin: 0 10px; 
            font-size: 16px; 
            font-weight: bold; 
            transition: color 0.3s ease, transform 0.3s ease; 
        }

        footer .social-media a:hover {
            color: #000000; 
            transform: scale(1.1); 
        }

        footer .social-media a::before {
            content: '🌐'; 
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            footer.footer {
                text-align: center; 
                padding: 20px 5%; 
            }

            footer .footer-links a,
            footer .social-media a {
                display: block; 
                margin: 5px 0; 
            }
        }

    </style>
</head>
<body>
    <section>
        <div>
            <a class="logo" href="index.php">
                <img src="image/LocalhandW.png" alt="Website Logo">
            </a>
        </div>
         <div class="navbar">
        <a href="index.php">Home</a>
        <a href="service_nonuser.php">Services</a>
        <a href="market_nonuser.php">Market Place</a>
        <a href="about_us.php">About Us</a>
        <a href="login.php">Login</a>
    </div>
    </section>
   

   <h2>Welcome to LocalHand</h2>

    <h2 class="second_h2">Always ready to repair, anytime, anywhere</h2>
    <h6>We will ensure your project is done quickly,<br>While keeping safety a top priority</h6>
    

    <form action="search.php" method="POST">
        <input type="text" name="search_query" class="search-bar" placeholder="Search work types...">
        <button type="submit">Search</button>
    </form>

     <!-- Footer -->
    <?php
    include('footer.php');
    ?>

</body>
</html>
