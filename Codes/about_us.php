<?php
// Include your header and any necessary files here
include('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - LocalHand</title>
    <style>
        /* General Styles */
        

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: rgb(0, 0, 1);
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
        
        .navbar a[href="about_us.php"] {
            color: #000000; 
            font-weight: bold;
        }
        /* nav end */


        h2, h4 {
            color: #ffffff;
            margin-bottom: 10px;
        }

        h4 {
            margin-bottom: 20px;
        }

        ul {
            margin: 20px 0;
            padding: 0 20px;
            list-style-type: disc;
        }

        a {
            color: #0066cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* About Us Container */
        .about-us-container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Slideshow Section */
        .slideshow {
            display: flex;
            overflow: hidden;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .slideshow .slides img {
            width: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Section Styles */
        section {
            margin-bottom: 40px;
        }

        .about-us-header {
            text-align: center;
            margin: 40px;
            padding: 20px;
        }

        .about-us-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .about-us-header p {
            font-size: 1.2rem;
            color: #555;
        }

        section {
            margin-bottom: 40px;
        }

        h2 {
            font-size: 1.8rem;
            color: #444;
            margin-bottom: 15px;
        }

        ul {
            padding-left: 20px;
            list-style-type: disc;
        }

        ul li {
            margin-bottom: 10px;
            line-height: 1.5;
        }

        strong {
            color: #333;
        }

        .project-objectives, .core-features, .worker-specific-features, .user-specific-features, .admin-features {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .project-objectives h2,
        .core-features h2,
        .worker-specific-features h2,
        .user-specific-features h2,
        .admin-features h2 {
            text-align: left;
            border-bottom: 2px solid #467187;
            padding-bottom: 5px;
        }
        /* Footer Section */
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


        /* Responsive Design */
        @media (max-width: 768px) {
            .about-us-header h1 {
                font-size: 2rem;
            }

            .about-us-header p {
                font-size: 1rem;
            }

            ul {
                padding-left: 10px;
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
        <a href="service.php">Services</a>
        <a href="market.php">Market Place</a>
         <a href="about_us.php">About Us</a>
        <a href="login.php">Login</a>
       
     </div>
    </section>

 <!-- Main Content -->
    <section class="about-us-header">
        <h4>LocalHand is a web-based service platform designed to connect users with skilled workers in their local area. The platform offers a seamless way for workers to register and showcase their skills, while users can easily search, hire, and review service providers based on location, specialty, and ratings. LocalHands aims to simplify the process of finding reliable service professionals while providing workers with better visibility and job opportunities.</h4>
    </section>

<!-- About Us Page Content -->
<div class="about-us-container">
    <!-- Slideshow Section -->
    <section class="slideshow">
        <div class="slides">
            <img src="image/image2.jpg" alt="LocalHands - Connecting Workers and Users">
            <img src="image/image5.jpeg" alt="Find Skilled Workers Near You">
            <img src="image/image6.webp" alt="Discover Local Experts">
        </div>
    </section>

    <!-- Project Objectives -->
      <!-- Project Objectives -->
    <section class="project-objectives">
        <h2>Project Objectives</h2>
        <ul>
            <li>Facilitate efficient connections between users and workers.</li>
            <li>Ensure secure and convenient user-worker interactions.</li>
            <li>Provide tools for workers to manage their profiles and availability.</li>
            <li>Empower users with comprehensive search and filtering options.</li>
        </ul>
    </section>

    <!-- Core Features -->
    <section class="core-features">
        <h2>Core Features</h2>
        <ul>
            <li><strong>Worker Registration:</strong> Workers can sign up and add details such as name, contact number, field of work, location, bio, and hourly rate.</li>
            <li><strong>Search for Workers:</strong> Users can search for workers by location, field of work, rating, or availability.</li>
            <li><strong>Ratings & Reviews:</strong> Users can rate workers (1-5 stars) and leave reviews.</li>
            <li><strong>Location-Based Search:</strong> Integration with Google Maps API to display workers near a user's location or sort by area.</li>
            <li><strong>Worker Profiles:</strong> Detailed worker profiles showing their ratings, reviews, and portfolio (optional).</li>
        </ul>
    </section>

    <!-- Worker-Specific Features -->
    <section class="worker-specific-features">
        <h2>Worker-Specific Features</h2>
        <ul>
            <li><strong>Availability Status:</strong> Workers can mark themselves as available or unavailable.</li>
            <li><strong>Portfolio Uploads:</strong> Workers can upload photos of their past work.</li>
            <li><strong>Job History:</strong> Workers can view completed jobs and associated reviews.</li>
        </ul>
    </section>

    <!-- User-Specific Features -->
    <section class="user-specific-features">
        <h2>User-Specific Features</h2>
        <ul>
            <li><strong>Job Requests:</strong> Users can post job requests for workers to respond to.</li>
            <li><strong>Save Favorites:</strong> Users can bookmark favorite workers for future use.</li>
            <li><strong>Contact Workers:</strong> Users can directly contact workers via call options.</li>
            <li><strong>Budget Filter:</strong> Search for workers within a specific budget range.</li>
        </ul>
    </section>

    <!-- Admin Features -->
    <section class="admin-features">
        <h2>Admin Features</h2>
        <ul>
            <li><strong>Admin Dashboard:</strong> Admin can approve or reject worker registrations and moderate reviews and reported content.</li>
            <li><strong>Analytics:</strong> Access insights into user and worker activity, such as popular locations or services.</li>
        </ul>
    </section>


    <!-- Footer -->
    <section class="footer">
        <p>At LocalHand, we are committed to making the process of finding reliable service providers as simple and efficient as possible. We are constantly working to improve the platform and add new features to better serve our users and workers.</p>
    </section>
</div>

<?php
// Include your footer file
include('footer.php');
?>

</body>
</html>
