<?php
// Include the database connection file
include('connection.php');
session_start();
// Enable error reporting for debugging
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);


// Initialize error messages array
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from the form
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $ph_no = $_POST['phone'];
    $password = $_POST['password']; // Keeping password as plain text (as requested)

    // Initialize the $filePath variable (in case there's no file uploaded)
    $filePath = '';;

    // Handle file upload (image) - only if a file is uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];

        // Set upload directory
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filePath = $uploadDir . basename($fileName);

        // Validate file type (only allow images)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
        }

        // Validate file size (e.g., 5MB max)
        if ($fileSize > 5000000) {
            $errors[] = "File is too large. Maximum size is 5MB.";
        }

        // If no errors, move the uploaded file to the server
        if (empty($errors) && !move_uploaded_file($fileTmpPath, $filePath)) {
            $errors[] = "Error uploading image.";
        }
    }

    // If there are any errors, return them in JSON
    if (!empty($errors)) {
        echo json_encode(["success" => false, "message" => implode(" ", $errors)]);
        exit;
    }

    // Prepare SQL query for inserting the user data
    $sql = "INSERT INTO customer (customer_name, picture, age, email, ph_no, password)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare and bind the query
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssisss", $name, $filePath, $age, $email, $ph_no, $password);

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id; // Store newly created user ID
            $_SESSION['user_name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['profile_picture'] = $filePath;
            $_SESSION['age']=$age;
            $_SESSION['phone']=$ph_no;
            header("Location:dashboard.php");
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Database error."]);
    }

    // Close the database connection
    $mysqli->close();
}

?>
