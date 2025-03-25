<?php
include("connection.php");
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch user details
$user_name = $_SESSION['user_name'];
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : "Email not found!";
$profile_picture = $_SESSION['profile_picture'] ??"";
$phone=$_SESSION['phone']??'Not provided';
$age=$_SESSION['age']??'Age';
$firstLetter = strtoupper($user_name[0]);
if (!empty($profile_picture)) {
    $profile_display = "<img src='$profile_picture' class='profile-img'>";
} else {
    $profile_display = "<div class='avatar'>$firstLetter</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Agency Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpeg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: rgba(187, 178, 236, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 1000px;
            display: flex;
        }
        .sidebar {
            width: 300px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: rgba(46, 25, 179, 0.667);
            border-radius: 10px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
            border: 2px solid #fff;
        }
        .profile-img img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #6a1b9a; /* Default avatar color */
            color: white;
            font-size: 40px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            overflow: hidden;
            margin-bottom: 20px;

        }
        .avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .info {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .menu ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }
        .menu li {
            padding: 15px;
            font-size: 18px;
            color: #eeeaf1;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            border-radius: 25px;
            transition: background 0.3s;
        }
        .menu li:hover {
            background: linear-gradient(to bottom, #57abdf, #df7ba2);
            transform: scale(1.1);
        }
        .buttons {
            margin-top: 20px;
            width: 100%;
            text-align: center;
        }
        .button {
            background-color: #6a1b9a;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            cursor: pointer;
            margin: 5px;
            font-size: 16px;
            width: 50%;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .button:hover {
            background: linear-gradient(to bottom, #f99090, #1005e9);
            transform: scale(1.1); 
        }
        .content-container {
            flex-grow: 1;
            padding: 20px;
            text-align: center;
        }
    </style>
    <script>
         function loadContent(page) {
            let contentBox = document.getElementById('content-box');
            let profileHTML = `
                <h2>Profile</h2>
                <div >
                    <?php echo $profile_display; ?>
                    <br><br>
                    <p><strong>Name:</strong> <?php echo $user_name; ?></p>
                    <p><strong>Age:</strong> <?php echo $age; ?></p>
                    <p><strong>Email:</strong> <?php echo $user_email; ?></p>
                    <p><strong>Phone:</strong> <?php echo $phone; ?></p>
                    <button onclick="window.location.href='edit.php'" class="button">Edit Profile</button>
                </div>
            `;

            if (page === 'profile') {
                contentBox.innerHTML = profileHTML;
            } else if (page === 'booking') {
                contentBox.innerHTML = '<h2>Booking History</h2><p>View all your past and current bookings here.</p>';
            } else if (page === 'favourites') {
                contentBox.innerHTML = '<h2>Favourites</h2><p>View and manage your favorite travel destinations.</p>';
            } else if (page === 'support') {
                contentBox.innerHTML = '<h2>Support</h2><p>Get assistance for your bookings and travel queries.</p>';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile-img">
                <?php echo $profile_display; ?>
            </div>
            <div class="info">
                <p>Welcome! <?php echo $user_name; ?></p>
            </div>
            <div class="menu">
                <ul>
                    <li onclick="loadContent('profile')">Profile</li>
                    <li onclick="loadContent('booking')">Booking History</li>
                    <li onclick="loadContent('favourites')">Favourites</li>
                    <li onclick="loadContent('support')">Support</li>
                </ul>
            </div>
            <div class="buttons">
                <button class="button" onclick="window.location.href='index.php'">Home</button>
                <button class="button" onclick="window.location.href='logout.php'">Log Out</button>
            </div>
        </div>
        <div class="content-container" id="content-box">
            <h2>Welcome to Your Travel Profile</h2>
            <p>Select an option from the menu to get started.</p>
        </div>
    </div>
</body>
</html>
