<?php

require_once 'dbh.inc.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO users (username, password, number, email, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $number, $email, $image);

    // Set parameters and execute
    $username = $_POST['username'];
    $password = $_POST['password'];
    $number = $_POST['number'];
    $email = $_POST['email'];

    // Handle image upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        $image = $targetFile;
    } else {
        $image = ""; // If image upload fails, set empty string
    }

    $response = array(); // Initialize response array

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "New record inserted successfully";
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();

    // Return response as JSON
    echo json_encode($response);
    exit(); // Stop further execution
}

?>