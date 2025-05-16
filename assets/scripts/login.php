<?php

// Connect to database
include 'connect.php';

// Set blank varibales
$email = $password = $error_msg = "";

// Get the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Write the SQL query to check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users_tb WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 1) {
        session_start();
        // if credentials is validated
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit;
    } else {
        // else it is not validated
        $error_msg = "Invalid email or password";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mzehemen U Tiv</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <link rel="icon" href="../images/main-logo.jpg" type="image/x-icon">
    <style>
        body {
            /* background: #f8f9fa; */
            position: relative;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        .bg-blur {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            background: url('../images/IMG-20250417-WA0010.jpg') no-repeat center center fixed;
            background-size: cover;
            filter: blur(3px) brightness(0.9);
      }   
            
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(30,94,53,0.08);
            background: rgba(255,255,255,0.97);
        }

        .brand-logo img {
            width: 60px;
        }
    </style>
</head>
<body>
  <div class="bg-blur"></div>
    <main class="login-container">
        <div class="card login-card p-4">
            <div class="text-center mb-4">
                <img src="../images/main-logo-transparent.png" alt="Mzehemen U Tiv" width="70">
                <h3 class="mt-2 text-primary">Login</h3>
                <p class="text-muted">Sign in to your account</p>
            </div>
            <form action="login.php" method="POST">
              <div class="text-center">
                <span class="text-danger"><?php echo $error_msg; ?></span>
              </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="container text-center pt-4">
                <p class="mb-0 text-muted small"> &COPY; 2025 Mzehemen U Tiv. All rights reserved.</p>
            </div>
        </div>
    </main>
    <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>