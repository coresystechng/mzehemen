<?php
include 'connect.php';

$email = "";
$response = "";

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    if (empty($email)) {
        $response = '<div class="error">Email is required.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = '<div class="error text-danger-emphasis">Invalid email format.</div>';
    } else {
        // Check for existing email
        $check_query = $conn->prepare("SELECT id FROM newsletter_tb WHERE email = ?");
        $check_query->bind_param("s", $email);
        $check_query->execute();
        $check_query->store_result();

        if ($check_query->num_rows > 0) {
            $response = '<div class="error text-danger">This email is already subscribed.</div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO newsletter_tb (email) VALUES (?)");
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                $response = '<div class="success text-white">Subscription successful!</div>';
            } else {
                $response = '<div class="error">Error: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }

        $check_query->close();
    }

    $conn->close();
}

echo $response;
?>
