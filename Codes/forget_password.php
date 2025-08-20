<?php
session_start();
include('db.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $message = 'Please enter your email address.';
    } else {
        try {
            // Check if the email exists in the database
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Generate a unique reset token
                $reset_token = bin2hex(random_bytes(16));
                $reset_expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valid for 1 hour

                // Update the token in the database
                $update_sql = "UPDATE users SET reset_token = :reset_token, reset_expiry = :reset_expiry WHERE email = :email";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([
                    'reset_token' => $reset_token,
                    'reset_expiry' => $reset_expiry,
                    'email' => $email
                ]);

                // Send the reset link to the user's email
                $reset_link = "http://yourwebsite.com/reset_password.php?token=$reset_token";
                $subject = "Password Reset Request";
                $body = "Hi, \n\nClick the link below to reset your password:\n$reset_link\n\nThis link will expire in 1 hour.\n\nThanks,\nYour Website Team";
                $headers = "From: no-reply@yourwebsite.com";

                if (mail($email, $subject, $body, $headers)) {
                    $message = "A password reset link has been sent to your email address.";
                } else {
                    $message = "Failed to send the reset link. Please try again.";
                }
            } else {
                $message = "No account found with that email address.";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h1 {
            text-align: center;
            color: #343a40;
        }

        form {
            margin-top: 20px;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <p>Please enter your email address to reset your password.</p>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
    </div>
</body>
</html>
