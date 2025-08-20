<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

// Convert the image BLOB to a data URL for displaying in the HTML
$profile_image = $user['image'] ? 'data:image/jpeg;base64,' . base64_encode($user['image']) : 'https://via.placeholder.com/150';

// Handle Edit Profile form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error_message = "All fields are required.";
    } else {
        // Update user data in the database
        $update_query = "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address WHERE user_id = :user_id";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $update_stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $update_stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $update_stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            header('Location: user_profile.php');
            exit();
        } else {
            $error_message = "Failed to update profile. Please try again.";
        }
    }
}

// Handle Upload Photo form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_photo'])) {
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        // Check file size (limit to 2MB)
        if ($_FILES['profile_photo']['size'] > 2 * 1024 * 1024) {
            $error_message = "File size exceeds 2MB. Please upload a smaller file.";
        } else {
            $file_tmp = $_FILES['profile_photo']['tmp_name'];
            $file_content = file_get_contents($file_tmp);

            // Update photo in the database
            $photo_query = "UPDATE users SET image = :image WHERE user_id = :user_id";
            $photo_stmt = $pdo->prepare($photo_query);
            $photo_stmt->bindParam(':image', $file_content, PDO::PARAM_LOB);
            $photo_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            if ($photo_stmt->execute()) {
                $success_message = "Photo uploaded successfully!";
                header('Location: user_profile.php');
                exit();
            } else {
                $error_message = "Failed to upload photo. Please try again.";
            }
        }
    } else {
        $error_message = "Please select a valid photo.";
    }
}

// Fetch booking history
$booking_query = "SELECT * FROM bookings WHERE user_id = :user_id ORDER BY booking_date DESC";
$booking_stmt = $pdo->prepare($booking_query);
$booking_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$booking_stmt->execute();
$booking_result = $booking_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch feedback history
$feedback_query = "SELECT * FROM reviews WHERE user_id = :user_id ORDER BY created_at DESC";
$feedback_stmt = $pdo->prepare($feedback_query);
$feedback_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$feedback_stmt->execute();
$feedback_result = $feedback_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: flex-start;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #467187;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            height: 100vh;
            box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-weight: 600;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .sidebar a:hover {
            background: rgb(72, 141, 170);
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
            background: white;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 20px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ddd;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-header h1 {
            font-size: 2rem;
            color: #333;
            font-weight: 600;
        }

        .profile-header p {
            font-size: 0.9rem;
            color: #666;
        }

        .button {
            background-color: #467187;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: rgb(89, 146, 174);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 5px;
        }

        .panel {
            margin-top: 30px;
        }

        .panel h2 {
            margin-bottom: 20px;
            font-weight: 600;
        }

        .panel ul {
            list-style-type: none;
            padding: 0;
        }

        .panel ul li {
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .upload-photo-panel input[type="file"] {
            padding: 10px;
            margin: 15px 0;
        }

        .upload-button {
            background-color: #467187;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload-button:hover {
            background-color: rgb(89, 146, 174);
        }

        .error-message,
        .success-message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 1rem;
        }

        .error-message {
            background-color: #f44336;
            color: white;
        }

        .success-message {
            background-color: #467187;
            color: white;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>My Profile</h2>
        <a href="user_dashboard.php">Dashboard</a>
        <a href="javascript:void(0);" onclick="togglePanel('profile-panel')">Edit Profile</a>
        <a href="javascript:void(0);" onclick="togglePanel('upload-photo-panel')">Upload Photo</a>
        <a href="javascript:void(0);" onclick="togglePanel('booking-history-panel')">Booking History</a>
        <a href="javascript:void(0);" onclick="togglePanel('feedback-history-panel')">Feedback History</a>
    </div>

    <div class="main-content">
        <div class="profile-header">
            <img src="<?php echo $profile_image; ?>" alt="Profile Photo">
            <div>
                <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
                <?php if (isset($success_message))
                    echo "<div class='success-message'>$success_message</div>"; ?>
                <?php if (isset($error_message))
                    echo "<div class='error-message'>$error_message</div>"; ?>
            </div>
        </div>

        <!-- Edit Profile Panel -->
        <div id="profile-panel" class="panel">
            <h2>Edit Profile</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address"
                        value="<?php echo htmlspecialchars($user['address']); ?>" required>
                </div>
                <button type="submit" name="edit_profile" class="button">Save Changes</button>
            </form>
        </div>

        <!-- Upload Photo Panel -->
        <div id="upload-photo-panel" class="panel">
            <h2>Upload Profile Photo</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profile_photo">Choose a photo to upload:</label>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" required>
                </div>
                <button type="submit" name="upload_photo" class="upload-button">Upload Photo</button>
            </form>
        </div>

        <!-- Booking History Panel -->
        <div id="booking-history-panel" class="panel">
            <h2>Booking History</h2>
            <?php if (empty($booking_result)): ?>
                <p>No booking history found.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($booking_result as $booking): ?>
                        <li>Booking ID: <?php echo $booking['booking_id']; ?> | Date: <?php echo $booking['booking_date']; ?> |
                            Status: <?php echo $booking['status']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Feedback History Panel -->
        <div id="feedback-history-panel" class="panel">
            <h2>Feedback History</h2>
            <?php if (empty($feedback_result)): ?>
                <p>No feedback history found.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($feedback_result as $feedback): ?>
                        <li>Review ID: <?php echo $feedback['review_id']; ?> | Rating: <?php echo $feedback['rating']; ?> |
                            Feedback: <?php echo $feedback['review_text']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function togglePanel(panelId) {
            var panels = document.querySelectorAll('.panel');
            panels.forEach(function (panel) {
                panel.style.display = 'none';
            });

            var activePanel = document.getElementById(panelId);
            if (activePanel) {
                activePanel.style.display = 'block';
            }
        }
    </script>
</body>

</html>