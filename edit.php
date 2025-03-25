<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
include 'connection.php'; // Include database connection

// Fetch user details from database
$id = $_SESSION['user_id'];
$sql = "SELECT customer_name, age, ph_no, email,picture FROM customer WHERE customer_id=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('background.jpeg') no-repeat center center/cover;
        }
        .container {
            background: rgba(227, 194, 226, 0.3);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            width: 350px;
            text-align: center;
            box-shadow: 0 0 10px rgba(64, 41, 71, 0.3);
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            outline: none;
            border-radius: 5px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: rgb(75, 39, 217);
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .profile-container {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
    }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            cursor: pointer;
            
        }
        .profile-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #6a0dad; /* Purple background */
            color: white;
            font-size: 40px;
            font-weight: bold;
            text-transform: uppercase;
            border: 2px solid #fff;
        }
    .profile-menu {
            display: none;
            position: absolute;
            top: 110px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-align: left;
            width: 150px;
        }
        .profile-menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .profile-menu li {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ccc;
        }
        .profile-menu li:hover {
            background: #eee;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Edit Profile</h2>
         
        <form action="profile_update.php" method="POST" enctype="multipart/form-data">
        <?php
        $profilePicture = !empty($user['picture']) ? htmlspecialchars($user['picture']) : '';
        $firstLetter = strtoupper(substr($user['customer_name'], 0, 1)); // Get first letter
        ?>
        <div class="profile-container" onclick="toggleMenu()">   
            <div id="profilePreview">
                <?php if ($profilePicture): ?>
                    <img src="<?= $profilePicture ?>" alt="Profile Picture" class="profile-pic">
                <?php else: ?>
                    <div class="profile-placeholder"><?= $firstLetter ?></div>
                <?php endif; ?>
            </div>

            <!-- Hidden Input for File Upload -->
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display: none;">
            
            <!-- Profile Options Menu -->
            <div class="profile-menu" id="profileMenu">
                <ul>
                    <li onclick="document.getElementById('profile_picture').click()">Choose File</li>
                    <li onclick="removeProfilePicture()">Remove Picture</li>
                </ul>
            </div>

            <!-- Hidden input for remove flag -->
            <input type="hidden" name="remove_picture" id="remove_picture" value="0">
        </div>

        
            <input type="text" name="name" value="<?= htmlspecialchars($user['customer_name']) ?>" required>
            <input type="number" name="age" value="<?= htmlspecialchars($user['age']) ?>" required>
            <input type="tel" name="phone" value="<?= htmlspecialchars($user['ph_no']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <input type="password" name="password" placeholder="Enter new password (leave blank if unchanged)">
            <button class="btn" type="submit">Save Changes</button>
        </form>
</div>
        <!-- JavaScript to Preview Image -->
<script>
function toggleMenu() {
        let menu = document.getElementById("profileMenu");
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    }

    document.getElementById('profile_picture').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let previewContainer = document.getElementById('profilePreview');
                previewContainer.innerHTML = `<img src="${e.target.result}" alt="Profile Picture" class="profile-pic">`;
            };
            reader.readAsDataURL(file);
        }
        hideMenu();
    });
    function chooseFile() { 
    document.getElementById('profile_picture').click();
}
    function removeProfilePicture() {
        let previewContainer = document.getElementById('profilePreview');
        let firstLetter = "<?= $firstLetter ?>"; 
        previewContainer.innerHTML = `<div class="profile-placeholder">${firstLetter}</div>`;
        document.getElementById('remove_picture').value = "1"; // Mark for backend removal
        
    }
    function hideMenu() {
    document.getElementById("profileMenu").style.display = "none";
}

    // Hide menu when clicking outside
    document.addEventListener("click", function(event) {
        let menu = document.getElementById("profileMenu");
        let profileContainer = document.querySelector(".profile-container");
        if (!profileContainer.contains(event.target)) {
           hideMenu();
        }
    });

</script>

</body>
</html>
