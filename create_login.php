<?php
include('connection.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Query to fetch the user data based on the email
    $sql = "SELECT password,customer_id,customer_name ,age,ph_no,picture FROM customer WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        die("Error preparing the statement: " . $mysqli->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Retrieve the stored password from the database
        $stored_password = $row['password'];
       
        if ($stored_password === $password) {
            // Password is correct
            $message = "You have successfully logged in. Redirecting to dashboard...";
            $message_type = "success";
            $_SESSION['user_id'] = $row['customer_id'];
            $_SESSION['user_name'] = $row['customer_name'];
            $_SESSION['email'] = $email;
            $_SESSION['profile_picture'] = $row['picture'] ?? 'default-profile.png';
            $_SESSION['age']=$row['age'];
            $_SESSION['phone']=$row['ph_no'];
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'dashboard.php';
                }, 4000);
            </script>";
        } else {
            // Password is incorrect
            $message = "Oops! The password you entered is incorrect. Please try again.";
            $message_type = "failure";
            $redirect_button = '<a href="login.html" class="btn">Try Again</a>';
        }
    } else {
        $message = "No user found with this email.";
        $message_type = "failure";
        $redirect_button = '<a href="login.html" class="btn">Try Again</a>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('background.jpeg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .message-container {
            color: white;
            padding: 20px 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 2s ease-in-out;
            width: 400px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .message {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            background-color: white;
            color: black;
            border-radius: 5px;
            font-weight: bold;
        }

        /* Success Message Styles */
        .success {
            background-color: #66bb6a;
        }

        /* Failure Message Styles */
        .failure {
            background-color: #f44336;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="message-container <?php echo $message_type; ?>">
        <?php if (isset($message)): ?>
            <h1><?php echo $message_type == 'success' ? 'Welcome Back' : 'Failed to Log In'; ?></h1>
            <p class="message"> <?php echo $message; ?></p>
            <?php if (isset($redirect_button)) echo $redirect_button; ?>
        <?php endif; ?>
    </div>
</body>
</html>
