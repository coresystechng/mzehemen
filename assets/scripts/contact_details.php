<?php

  include 'connect.php';
  session_start();

  // Check if user is logged in
  if (!isset($_SESSION['email'])) {
      header('Location: login.php');
      exit();
  }

  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
      session_unset();
      session_destroy();
      header('Location: login.php?timeout=1');
      exit();
  }
  $_SESSION['LAST_ACTIVITY'] = time();

  // Get contact ID from query string
  if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
      echo "<p>Invalid contact ID.</p>";
      exit();
  }

  $contact_id = intval($_GET['id']);

  // Fetch contact details
  $stmt = $conn->prepare("SELECT * FROM contact_us_tb WHERE id = ?");
  $stmt->bind_param("i", $contact_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
      echo "<p>Contact message not found.</p>";
      exit();
  }

  $contact = $result->fetch_assoc();
  $stmt->close();
  $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Message Details - Mzehemen U Tiv</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <link rel="icon" href="../images/main-logo.jpg" type="image/x-icon">
    <style>
    body { padding-top: 0 !important; }
    </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
        <img src="../images/main-logo.jpg" alt="Logo" width="40" height="40" class="d-inline-block align-text-top rounded-circle me-2">
        <span class="fw-bold">Mzehemen U Tiv</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
        <form action="logout.php" class="d-flex ms-lg-3 mt-3 mt-lg-0">
          <button type="submit" class="btn btn-danger btn-lg px-3 w-100">Logout</button>
        </form>
      </div>
    </div>
  </nav>
  <div class="container py-5">
      <a href="contacts_all.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Back to All Contacts</a>
      <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Contact Message Details</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle mb-0">
                <tbody>
                  <tr>
                    <th scope="row" style="width:40%">ID</th>
                    <td><?php echo htmlspecialchars($contact['id']); ?></td>
                  </tr>
                  <tr>
                    <th scope="row">Name</th>
                    <td><?php echo htmlspecialchars($contact['full_name'] ?? ''); ?></td>
                  </tr>
                  <tr>
                    <th scope="row">Email</th>
                    <td>
                      <?php if (!empty($contact['email'])): ?>
                        <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>">
                          <?php echo htmlspecialchars($contact['email']); ?>
                        </a>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">Subject</th>
                    <td><?php echo htmlspecialchars($contact['subject'] ?? ''); ?></td>
                  </tr>
                  <tr>
                    <th scope="row">Message</th>
                    <td><?php echo nl2br(htmlspecialchars($contact['message'] ?? '')); ?></td>
                  </tr>
                  <tr>
                    <th scope="row">Date Submitted</th>
                    <td>
                      <?php
                        if (!empty($contact['date_submitted'])) {
                          $date = date('F j, Y', strtotime($contact['date_submitted']));
                          echo htmlspecialchars($date);
                        }
                      ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
      </div>
  </div>
  <div class="container text-center py-5">
    <p class="mb-0 text-muted small"> &COPY; 2025 Mzehemen U Tiv. All rights reserved.</p>
  </div>
  <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>