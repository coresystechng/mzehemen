<?php
  include 'connect.php';

  // Start session
  session_start();

  if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
  }

  $logged_user = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
  <link rel="icon" href="../images/main-logo.jpg" type="image/x-icon">
  <link rel="shortcut icon" href="../images/main-logo.jpg" type="image/x-icon">
  <title>Dashboard - Mzehemen U Tiv</title>
</head>
<body>
  <div class="container py6 text-center">
    <h class="display-5"><?php echo "Welcome $logged_user."; ?></h3>
    <form action="logout.php">
      <button type="submit" class="btn btn-lg btn-danger px-3">Logout</button>
    </form>
  </div>

  <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>