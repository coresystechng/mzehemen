<?php

// Database connection
include 'connect.php';

// Initialize variables
$full_name = "";
$email = "";
$subject = "";
$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $full_name = htmlspecialchars(trim($_POST["full_name"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $subject = htmlspecialchars(trim($_POST["subject"] ?? ''));
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    // Validate inputs
    if (empty($full_name) || empty($email) || empty($subject) || empty($message)) {
        $response = '<div class="error text-danger-emphasis">All fields are required!</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = '<div class="error text-danger">Invalid email format.</div>';;
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO contact_us_tb (full_name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $email, $subject, $message);

        if ($stmt->execute()) {
            // Redirect to success page
            header("Location: success.html");
            exit();
        } else {
            echo 'Database error: ' . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}

?>