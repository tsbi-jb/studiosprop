<?php
// admin_dashboard.php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .container { max-width: 1000px; margin-top: 40px; }
    .nav-tabs .nav-link.active { font-weight: bold; }
    table td, table th { vertical-align: middle; }
  </style>
</head>
<body>
<div class="container">
  <h3 class="text-center mb-4">TSBI Studios Admin Dashboard</h3>
  <ul class="nav nav-tabs" id="adminTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="services-tab" data-toggle="tab" href="#services" role="tab">Services</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="quotes-tab" data-toggle="tab" href="#quotes" role="tab">Quotes</a>
    </li>
    <li class="nav-item ml-auto">
      <a class="nav-link text-danger" href="logout.php">Logout</a>
    </li>
  </ul>
  <div class="tab-content" id="adminTabContent">
    <div class="tab-pane fade show active" id="services" role="tabpanel">
      <?php include 'admin_services.php'; ?>
    </div>
    <div class="tab-pane fade" id="quotes" role="tabpanel">
      <?php include 'admin_quotes.php'; ?>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>  
