<?php
  include 'connect.php';

  // Start session
  session_start();

  if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
  }

  $logged_user = $_SESSION['email'];

  // Retrieve Data from Contact Table
  $query = "SELECT * FROM contact_us_tb";
  $result = mysqli_query($conn, $query);
  $contact_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);

  // Retrieve Data from Nominations Table
  $query = "SELECT * FROM nominations_tb";
  $result = mysqli_query($conn, $query);
  $nominations_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);

  // Retrieve Data from Volunteers Table
  $query = "SELECT * FROM volunteers_tb";
  $result = mysqli_query($conn, $query);
  $volunteers_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);

  // Retrieve Data from Newsletter Table
  $query = "SELECT * FROM newsletter_tb";
  $result = mysqli_query($conn, $query);
  $newsletter_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);

  mysqli_close($conn);
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
  <title>Dashboard - Mzehemen U Tiv</title>
  <style>
    body {
      padding-top: 0 !important;
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
      <!-- Logo on the left -->
      <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
        <img src="../images/main-logo.jpg" alt="Logo" width="40" height="40" class="d-inline-block align-text-top rounded-circle me-2">
        <span class="fw-bold">Mzehemen U Tiv</span>
      </a>
      <!-- Toggler/collapsible Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Navbar links and logout -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item nav-hover">
            <a class="nav-link " href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item nav-hover">
            <a class="nav-link" href="nominations.php">Nominations</a>
          </li>
          <li class="nav-item nav-hover">
            <a class="nav-link" href="volunteers.php">Volunteers</a>
          </li>
          <li class="nav-item nav-hover">
            <a class="nav-link" href="newsletter.php">Newsletter</a>
          </li>
        </ul>
        <form action="logout.php" class="d-flex ms-lg-3 mt-3 mt-lg-0">
          <button type="submit" class="btn btn-danger btn-lg px-3 w-100">Logout</button>
        </form>
      </div>
    </div>
  </nav>
  <!-- Dashboard Content -->
  <div class="container">
    <h6 class="display-5 py-5">Welcome <?php echo $logged_user;?></h6>
    <div class="row g-4">
      <div class="col-md-6">
        <a href="nominations.php" class="text-decoration-none">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-award fs-1 text-primary"></i>
              <h5 class="card-title mt-3">Nominations</h5>
              <p class="card-text">View and manage nominations.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6">
        <a href="contact_us.php" class="text-decoration-none">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-envelope fs-1 text-success"></i>
              <h5 class="card-title mt-3">Contact Us</h5>
              <p class="card-text">See messages from the contact form.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6">
        <a href="newsletter.php" class="text-decoration-none">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-newspaper fs-1 text-warning"></i>
              <h5 class="card-title mt-3">Newsletter</h5>
              <p class="card-text">Manage newsletter subscriptions.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6">
        <a href="volunteers.php" class="text-decoration-none">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-people fs-1 text-danger"></i>
              <h5 class="card-title mt-3">Volunteers</h5>
              <p class="card-text">View and manage volunteers.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <div class="container text-center py-5">
    <p class="mb-0 text-muted small"> &COPY; 2025 Mzehemen U Tiv. All rights reserved.</p>
  </div>

  <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>