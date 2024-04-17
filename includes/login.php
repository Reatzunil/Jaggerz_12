<?php

require_once 'dbh.inc.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM user_details WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there is a matching record
    if ($result->num_rows > 0) {
        // Credentials are correct
        session_start();
        $_SESSION['username'] = $username; // Store username in session for later use
        // Redirect to a specific page or display a success message
        header("Location: user.php"); // Redirect to dashboard page
        exit(); // Stop further execution
    } else {
        // Credentials are incorrect
        echo "Invalid username or password.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>