<?php
session_start();
include 'includes/db.php';

// Hardcoded credentials
$admin_user = 'admin';
$admin_pass = 'password123';

// Login
if (isset($_POST['login'])) {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  if ($username === $admin_user && $password === $admin_pass) {
    $_SESSION['admin_logged_in'] = true;
  } else {
    $error = "Invalid credentials.";
  }
}

// Logout
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: admin.php");
  exit;
}

// Require login
if (!isset($_SESSION['admin_logged_in'])):
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="text-center mt-4">
    <a href="index.html" class="btn btn-outline-secondary">User Module</a>
  </div>
  
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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .container { max-width: 1100px; margin-top: 40px; }
    .nav-tabs .nav-link.active { font-weight: bold; }
    table td, table th { vertical-align: middle; }
  </style>
</head>
<body>
<div class="d-flex justify-content-between align-items-center px-4 mt-3 mb-2">
  <img src="assets/logo.png" alt="Company Logo" style="max-height: 80px;">
</div>

<div class="container">
  <h3 class="text-center mb-4">TSBI Studios Admin Dashboard</h3>
  <ul class="nav nav-tabs" id="adminTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="services-tab" data-toggle="tab" href="#services" role="tab">Manage Services</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="quotes-tab" data-toggle="tab" href="#quotes" role="tab">View Quotes</a>
    </li>
    <li class="nav-item ml-auto">
  <form method="GET" action="" class="mb-0">
    <button type="submit" name="logout" value="1" class="btn btn-danger nav-link text-white" style="border: none;">
      Logout
    </button>
  </form>
</li>

  </ul>

  <div class="tab-content mt-3" id="adminTabContent">
    <div class="tab-pane fade show active" id="services" role="tabpanel">
      <?php include 'admin_services.php'; ?>
    </div>
    <div class="tab-pane fade" id="quotes" role="tabpanel">
      <?php include 'admin_quotes.php'; ?>
    </div>
  </div>
</div>

<!-- JS dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Smart Tab Activation -->
<script>
  $(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const hash = window.location.hash;

    const isQuotesTabRequest = (
      hash === '#quotes' ||
      (urlParams.has('search') || urlParams.has('page'))
    );

    if (isQuotesTabRequest) {
      $('#adminTab a[href="#quotes"]').tab('show');
    } else {
      // Default to services tab for all other cases
      $('#adminTab a[href="#services"]').tab('show');
    }

    // Maintain tab on navigation
    $('#adminTab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      history.replaceState(null, null, e.target.hash);
    });
  });
</script>

</body>
</html>
