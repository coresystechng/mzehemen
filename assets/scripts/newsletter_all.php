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

  // Pagination setup
  $limit = 10;
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
  if ($page < 1) $page = 1;
  $offset = ($page - 1) * $limit;

  // Get total subscribers count
  $total_query = "SELECT COUNT(*) as total FROM newsletter_tb";
  $total_result = mysqli_query($conn, $total_query);
  $total_row = mysqli_fetch_assoc($total_result);
  $total_subscribers = $total_row['total'];
  $total_pages = ceil($total_subscribers / $limit);

  // Fetch subscribers for current page
  $stmt = $conn->prepare("SELECT * FROM newsletter_tb ORDER BY id DESC LIMIT ? OFFSET ?");
  $stmt->bind_param("ii", $limit, $offset);
  $stmt->execute();
  $result = $stmt->get_result();
  $subscribers = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  $conn->close();
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
    <title>All Subscribers - Mzehemen U Tiv</title>
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
  <div class="container">
      <a href="dashboard.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
      <h2 class="mb-4">All Newsletter Subscribers</h2>
      <?php if (!empty($subscribers)): ?>
      <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Email</th>
                <th>Date</th>
              </tr>
            </thead>
              <tbody>
              <?php foreach ($subscribers as $row): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                    <?php
                      $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date_of_subscription']);
                      echo $date ? $date->format('M j, Y') : htmlspecialchars($row['date_of_subscription']);
                    ?>
                    </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
          </table>
      </div>
      <!-- Pagination -->
      <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center">
              <?php if ($page > 1): ?>
                  <li class="page-item">
                      <a class="page-link" href="?page=<?php echo $page-1; ?>" aria-label="Previous">
                          <span aria-hidden="true">&laquo; Prev</span>
                      </a>
                  </li>
              <?php endif; ?>
              <?php
              $start = max(1, $page - 2);
              $end = min($total_pages, $page + 2);
              for ($i = $start; $i <= $end; $i++): ?>
                  <li class="page-item<?php if ($i == $page) echo ' active'; ?>">
                      <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                  </li>
              <?php endfor; ?>
              <?php if ($page < $total_pages): ?>
                  <li class="page-item">
                      <a class="page-link" href="?page=<?php echo $page+1; ?>" aria-label="Next">
                          <span aria-hidden="true">Next &raquo;</span>
                      </a>
                  </li>
              <?php endif; ?>
          </ul>
      </nav>
      <?php else: ?>
          <p>No subscribers found.</p>
      <?php endif; ?>
  </div>
  <div class="container text-center py-5">
    <p class="mb-0 text-muted small"> &COPY; 2025 Mzehemen U Tiv. All rights reserved.</p>
  </div>
<script src="../js/bootstrap.bundle.js"></script>
</body>
</html>