<?php
// filepath: c:\xampp\htdocs\mzehemen\assets\scripts\nomination_details.php

include 'connect.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
    // Last request was more than 10 minutes ago
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit();
  }
  $_SESSION['LAST_ACTIVITY'] = time();

// Get nomination ID from query string
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Invalid nomination ID.</p>";
    exit();
}

$nomination_id = intval($_GET['id']);

// Fetch nomination details
$stmt = $conn->prepare("SELECT * FROM nominations_tb WHERE id = ?");
$stmt->bind_param("i", $nomination_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Nomination not found.</p>";
    exit();
}

$nomination = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomination Details - Mzehemen U Tiv</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <link rel="icon" href="../images/main-logo.jpg" type="image/x-icon">
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
        <form action="logout.php" class="d-flex ms-lg-3 mt-3 mt-lg-0">
          <button type="submit" class="btn btn-danger btn-lg px-3 w-100">Logout</button>
        </form>
      </div>
    </div>
  </nav>
  <div class="container py-5">
      <a href="nominations_all.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Back to Nominations</a>
      <div class="card shadow">
          <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Nomination Details</h4>
          </div>
          <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
          <tbody>
              <tr>
            <th scope="row" style="width:40%">Nominator Name</th>
            <td><?php echo htmlspecialchars($nomination['nominator_name']); ?></td>
              </tr>
              <tr>
            <th scope="row">Nominator Email</th>
            <td>
                <?php if (!empty($nomination['nominator_email'])): ?>
                <a href="mailto:<?php echo htmlspecialchars($nomination['nominator_email']); ?>">
              <?php echo htmlspecialchars($nomination['nominator_email']); ?>
                </a>
                <?php endif; ?>
            </td>
              </tr>
              <tr>
            <th scope="row">Nominator Phone</th>
            <td>
                <?php if (!empty($nomination['nominator_phone'])): ?>
                <a href="tel:<?php echo htmlspecialchars($nomination['nominator_phone']); ?>">
              <?php echo htmlspecialchars($nomination['nominator_phone']); ?>
                </a>
                <?php endif; ?>
            </td>
              </tr>
              <tr>
            <th scope="row">Nominator Relation</th>
            <td><?php echo htmlspecialchars($nomination['nominator_relation'] ?? ''); ?></td>
              </tr>
              <tr>
            <th scope="row">Nominee Name</th>
            <td><?php echo htmlspecialchars($nomination['nominee_name']); ?></td>
              </tr>
              <tr>
            <th scope="row">Nominee Email</th>
            <td>
                <?php if (!empty($nomination['nominee_email'])): ?>
                <a href="mailto:<?php echo htmlspecialchars($nomination['nominee_email']); ?>">
              <?php echo htmlspecialchars($nomination['nominee_email']); ?>
                </a>
                <?php endif; ?>
            </td>
              </tr>
              <tr>
            <th scope="row">Nominee Age</th>
            <td><?php echo htmlspecialchars($nomination['nominee_age'] ?? ''); ?></td>
              </tr>
              <tr>
            <th scope="row">Nominee Gender</th>
            <td><?php echo htmlspecialchars($nomination['nominee_gender'] ?? ''); ?></td>
              </tr>
              <tr>
            <th scope="row">Nominee Phone</th>
            <td>
                <?php if (!empty($nomination['nominee_phone'])): ?>
                <a href="tel:<?php echo htmlspecialchars($nomination['nominee_phone']); ?>">
              <?php echo htmlspecialchars($nomination['nominee_phone']); ?>
                </a>
                <?php endif; ?>
            </td>
              </tr>
              <tr>
            <th scope="row">Nominee Address</th>
            <td><?php echo htmlspecialchars($nomination['nominee_address'] ?? ''); ?></td>
              </tr>
              <tr>
            <th scope="row">Award Category</th>
            <td><?php echo htmlspecialchars($nomination['award_category']); ?></td>
              </tr>
              <tr>
            <th scope="row">Reason</th>
            <td><?php echo nl2br(htmlspecialchars($nomination['nomination_reason'])); ?></td>
              </tr>
              <tr>
            <th scope="row">Before Contribution</th>
            <td><?php echo nl2br(htmlspecialchars($nomination['before_contribution'] ?? '')); ?></td>
              </tr>
              <tr>
            <th scope="row">After Contribution</th>
            <td><?php echo nl2br(htmlspecialchars($nomination['after_contribution'] ?? '')); ?></td>
              </tr>
              <tr>
            <th scope="row">Unique Contribution</th>
            <td><?php echo nl2br(htmlspecialchars($nomination['unique_contribution'] ?? '')); ?></td>
              </tr>
              <tr>
            <th scope="row">Supporting Document 1</th>
            <td>
                <?php if (!empty($nomination['supporting_doc_1'])): ?>
                <a href="<?php echo htmlspecialchars($nomination['supporting_doc_1']); ?>" target="_blank"><i class="bi bi-paperclip"></i> View Document</a>
                <?php else: ?>
                <span class="text-muted">No file uploaded</span>
                <?php endif; ?>
            </td>
              </tr>
              <tr>
            <th scope="row">Supporting Document 2</th>
            <td>
                <?php if (!empty($nomination['supporting_doc_2'])): ?>
                <a href="<?php echo htmlspecialchars($nomination['supporting_doc_2']); ?>" target="_blank"><i class="bi bi-paperclip"></i> View Document</a>
                <?php else: ?>
                <span class="text-muted">No file uploaded</span>
                <?php endif; ?>
            </td>
              </tr>
              <tr>
            <th scope="row">Additional Evidence</th>
            <td>
                <?php if (!empty($nomination['additional_evidence'])): ?>
                <a href="<?php echo htmlspecialchars($nomination['additional_evidence']); ?>" target="_blank"><i class="bi bi-paperclip"></i> View Document</a>
                <?php else: ?>
                <span class="text-muted">No file uploaded</span>
                <?php endif; ?>
            </td>
              </tr>
              <tr>
            <th scope="row">Date Submitted</th>
            <td>
                <?php
                if (!empty($nomination['created_at'])) {
              $date = date('F j, Y', strtotime($nomination['created_at']));
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
  <!-- Footer -->

  <div class="container text-center py-5">
    <p class="mb-0 text-muted small"> &COPY; 2025 Mzehemen U Tiv. All rights reserved.</p>
  </div>
    <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>