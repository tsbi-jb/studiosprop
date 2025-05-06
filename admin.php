<?php
session_start();
include 'includes/db.php';

// Hardcoded admin credentials (can be moved to DB later)
$admin_user = 'admin';
$admin_pass = 'password123';

// Handle login
if (isset($_POST['login'])) {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  if ($username === $admin_user && $password === $admin_pass) {
    $_SESSION['admin_logged_in'] = true;
  } else {
    $error = "Invalid credentials.";
  }
}

// Handle logout
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: admin.php");
  exit;
}

// Ensure authentication
if (!isset($_SESSION['admin_logged_in'])):
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<div class="text-center mt-4">
    <a href="index.html" class="btn btn-outline-secondary">User Module</a>
  </div>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card p-4 shadow">
      <h3 class="mb-4">Admin Login</h3>
      <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
      <form method="POST">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
<?php exit; endif; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center">
    <h2>Admin Dashboard</h2>
    <a href="?logout=1" class="btn btn-danger">Logout</a>
  </div>
  <hr>
  <ul class="nav nav-tabs" id="adminTabs">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#services">Manage Services</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#quotes">View Quotes</a>
    </li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane fade show active" id="services">
      <?php include 'admin_services.php'; ?>
    </div>
    <div class="tab-pane fade" id="quotes">
      <?php include 'admin_quotes.php'; ?>
    </div>
  </div>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
