<?php
  include 'connect.php';

  // Start session
  session_start();

  if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
  }

  // Get logged in user's email
  $logged_user = $_SESSION['email'];

  // Fetch first_name and last_name from users_tb
  $stmt = $conn->prepare("SELECT first_name, last_name FROM users_tb WHERE email = ?");
  $stmt->bind_param("s", $logged_user);
  $stmt->execute();
  $stmt->bind_result($first_name, $last_name);
  if ($stmt->fetch()) {
    $logged_user = $first_name . ' ' . $last_name;
  } else {
    $logged_user = $_SESSION['email'];
  }
  $stmt->close();

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

    .dashboard-card.active .card,
    .dashboard-card.selected .card {
      border-bottom: 4px solid #1e5e35 !important;
      /* You can change the color as needed */
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
  <!-- Dashboard Main Card Nav Content -->
  <div class="container">
    <h6 class="display-5 py-5">Welcome, <?php echo $logged_user;?></h6>
    <div class="row g-4">
      <div class="col-md-3">
        <a href="#" class="text-decoration-none dashboard-card active" data-section="nominations">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-award fs-1 text-primary"></i>
              <h5 class="card-title mt-3">Nominations</h5>
              <p class="card-text">View and manage nominations.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="#" class="text-decoration-none dashboard-card" data-section="contact">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-envelope fs-1 text-success"></i>
              <h5 class="card-title mt-3">Contact Us</h5>
              <p class="card-text">See messages from the contact form.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="#" class="text-decoration-none dashboard-card" data-section="newsletter">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-newspaper fs-1 text-warning"></i>
              <h5 class="card-title mt-3">Newsletter</h5>
              <p class="card-text">Manage newsletter subscriptions.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="#" class="text-decoration-none dashboard-card" data-section="volunteers">
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

  <!-- Data Sections -->
  <div class="container mt-5">
    <!-- Nominations Section -->
    <div class="dashboard-section" id="section-nominations">
      <h4 class="py-3">Nominations</h4>
      <?php if (!empty($nominations_data)): ?>
      <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th class="d-none d-md-table-cell">Nominator</th>
            <th>Nominee</th>
            <th>Award</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($nominations_data as $row): ?>
          <tr>
            <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($row['nominator_name']); ?></td>
            <td><?php echo htmlspecialchars($row['nominee_name']); ?></td>
            <td><?php echo htmlspecialchars($row['award_category']); ?></td>
            <td>
              <a href="nomination_details.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-sm btn-outline-primary">Details</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
      <div class="text-center mt-4">
        <a href="nominations.php" class="btn btn-primary btn-lg px-5">View All Nominations</a>
      </div>
      <?php else: ?>
      <p>No nominations found.</p>
      <?php endif; ?>
    </div>
    <!-- Contact Us Section -->
    <div class="dashboard-section d-none" id="section-contact">
      <h4 class="py-3">Contact Us Messages</h4>
      <?php if (!empty($contact_data)): ?>
      <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Email</th>
            <th class="d-none d-md-table-cell">Subject</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
        <?php
        // Show only the last 5 contact messages
        $last_five_contacts = array_slice($contact_data, -5, 5, true);
        foreach($last_five_contacts as $row):
        ?>
        <tr>
        <td ><?php echo htmlspecialchars($row['id']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($row['subject']); ?></td>
        <td>
          <a href="contact_details.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-sm btn-outline-primary">Details</a>
        </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      </div>
      <div class="text-center mt-4">
      <a href="contact_us.php" class="btn btn-primary btn-lg px-5">View All Contacts</a>
      </div>
      <?php else: ?>
      <p>No contact messages found.</p>
      <?php endif; ?>
    </div>
    <!-- Newsletter Section -->
    <div class="dashboard-section d-none" id="section-newsletter">
      <h4 class="py-3">Newsletter Subscribers</h4>
      <?php if (!empty($newsletter_data)): ?>
      <div class="table-responsive">
      <table class="table table-striped table-hover">
      <thead>
        <tr>
        <th>ID</th>
        <th>Email</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Show only the last 5 subscribers
        $last_five = array_slice($newsletter_data, -5, 5, true);
        foreach($last_five as $row):
        ?>
        <tr>
        <td><?php echo htmlspecialchars($row['id']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      </table>
      </div>
      <div class="text-center mt-4">
      <a href="newsletter.php" class="btn btn-primary btn-lg px-5">View All Subscribers</a>
      </div>
      <?php else: ?>
      <p>No newsletter subscribers found.</p>
      <?php endif; ?>
    </div>
    <!-- Volunteers Section -->
    <div class="dashboard-section d-none" id="section-volunteers">
      <h4>Volunteers</h4>
      <?php if (!empty($volunteers_data)): ?>
      <div class="table-responsive">
      <table class="table table-striped table-hover">
      <thead>
        <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Availability</th>
        <th>Details</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Show only the last 5 volunteers
        $last_five_volunteers = array_slice($volunteers_data, -5, 5, true);
        foreach($last_five_volunteers as $row):
        ?>
        <tr>
        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
        <td><?php echo htmlspecialchars($row['availability']); ?></td>
        <td>
        <a href="volunteer_details.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-sm btn-outline-primary">Details</a>
        </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      </table>
      </div>
      <div class="text-center mt-4">
      <a href="volunteers.php" class="btn btn-primary btn-lg px-5">View All Volunteers</a>
      </div>
      <?php else: ?>
      <p>No volunteers found.</p>
      <?php endif; ?>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.dashboard-card');
      cards.forEach(card => {
        card.addEventListener('click', function(e) {
          e.preventDefault();
          cards.forEach(c => c.classList.remove('active', 'selected'));
          card.classList.add('active');
        });
      });
      // Optionally, set the first card as active on load
      if (cards.length) cards[0].classList.add('active');
    });
  </script>

  <script>
    // Show corresponding section when card is clicked
    document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.dashboard-card');
      const sections = document.querySelectorAll('.dashboard-section');
      cards.forEach(card => {
        card.addEventListener('click', function(e) {
          e.preventDefault();
          const section = card.getAttribute('data-section');
          sections.forEach(sec => {
            if (sec.id === 'section-' + section) {
              sec.classList.remove('d-none');
            } else {
              sec.classList.add('d-none');
            }
          });
          // Scroll to the section
          document.getElementById('section-' + section).scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
      });
      // Show nominations by default
      sections.forEach(sec => {
        if (sec.id !== 'section-nominations') sec.classList.add('d-none');
        else sec.classList.remove('d-none');
      });
    });
  </script>

  <script>
    // Card click to switch tab
    document.addEventListener('DOMContentLoaded', function() {
      const cardLinks = [
        { selector: 'a[href="nominations.php"]', tab: 'nominations-tab' },
        { selector: 'a[href="contact_us.php"]', tab: 'contact-tab' },
        { selector: 'a[href="newsletter.php"]', tab: 'newsletter-tab' },
        { selector: 'a[href="volunteers.php"]', tab: 'volunteers-tab' }
      ];
      cardLinks.forEach(link => {
        const el = document.querySelector(link.selector);
        if (el) {
          el.addEventListener('click', function(e) {
            e.preventDefault();
            const tabTrigger = document.getElementById(link.tab);
            if (tabTrigger) {
              new bootstrap.Tab(tabTrigger).show();
              window.scrollTo({ top: document.querySelector('.nav-tabs').offsetTop - 60, behavior: 'smooth' });
            }
          });
        }
      });
    });
  </script>
  <!-- Footer -->

  <div class="container text-center py-5">
    <p class="mb-0 text-muted small"> &COPY; 2025 Mzehemen U Tiv. All rights reserved.</p>
  </div>

  <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>