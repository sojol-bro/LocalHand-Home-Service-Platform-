<?php
include 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $role = $_POST['role']; // 'user' or 'worker'
    $address = isset($_POST['address']) ? $_POST['address'] : null;
    $specialty = isset($_POST['specialty']) ? $_POST['specialty'] : null;

    try {
        if ($role === 'worker') {
            $stmt = $pdo->prepare(
                "INSERT INTO workers (name, phone, email, password, address, specialty) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$name, $phone, $email, $password, $address, $specialty]);
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$name, $email, $password, $phone, $role]);
        }
        $message = "<p style='color: green;'>Registration successful!</p>";
    } catch (PDOException $e) {
        $message = "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        error_log($e->getMessage()); // Log error
    }
}

$roleSelected = isset($_POST['role']) ? $_POST['role'] : 'user';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Reset and global styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #a7bcd1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Signup container styling */
        .signup-container {
            display: flex;
            background: #c2cff5;
            max-width: 750px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Image/Welcome section */
        .signup-image {
            flex: 1;
            background-color: #4952bb;
            background-size: cover;
            background-position: center;
            color: #ffffff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .signup-image h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .signup-image p {
            font-size: 14px;
        }

        /* Form section */
        .signup-form {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .signup-form h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        /* Form group styling */
        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-weight: 500;
            color: #333333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4952bb;
            outline: none;
        }

        /* Signup button */
        .signup-button {
            width: 100%;
            padding: 10px;
            background-color: #4952bb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 8px;
        }

        .signup-button:hover {
            background-color: #333333;
        }

        /* Additional links */
        .additional-links {
            margin-top: 8px;
            text-align: center;
        }

        .additional-links a {
            color: #4952bb;
            text-decoration: none;
            font-weight: 500;
        }

        .additional-links a:hover {
            text-decoration: underline;
        }

        /* Message section for success or error */
        .message {
            margin-top: 12px;
            font-size: 14px;
        }

        /* Worker-specific fields */
        #worker-fields {
            display: none; /* Default hidden */
        }

    </style>
    <script>
        function toggleWorkerFields() {
            const role = document.getElementById('role').value;
            const workerFields = document.getElementById('worker-fields');
            workerFields.style.display = role === 'worker' ? 'block' : 'none';
        }

        // Ensure the fields are set correctly on page load
        document.addEventListener('DOMContentLoaded', () => {
            toggleWorkerFields();
        });
    </script>
</head>
<body>
    <div class="signup-container">
        <div class="signup-image">
            <h2>Welcome!</h2>
            <p>Join our community by creating your account.</p>
        </div>
        <div class="signup-form">
            <h2>Sign Up</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" onchange="toggleWorkerFields()" required>
                        <option value="user" <?php echo ($roleSelected === 'user') ? 'selected' : ''; ?>>User</option>
                        <option value="worker" <?php echo ($roleSelected === 'worker') ? 'selected' : ''; ?>>Worker</option>
                    </select>
                </div>
                <div class="form-group" id="worker-fields">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address">
                    <label for="specialty">Specialty:</label>
                    <input type="text" id="specialty" name="specialty">
                </div>
                <button type="submit" class="signup-button">Sign Up</button>
                <div class="additional-links">
                    <a href="login.php">Already have an account? Login</a>
                </div>
            </form>
            <div class="message">
                <?php echo $message; ?>
            </div>
        </div>
    </div>
</body>
</html>
