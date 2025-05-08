<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include 'includes/db.php';

function serviceExists($conn, $name, $exclude_id = null) {
  $name = strtolower(trim($name));
  $stmt = $conn->prepare("SELECT id FROM studio_services WHERE LOWER(service_name) = ?" . ($exclude_id ? " AND id != ?" : ""));
  if ($exclude_id) {
    $stmt->bind_param("si", $name, $exclude_id);
  } else {
    $stmt->bind_param("s", $name);
  }
  $stmt->execute();
  return $stmt->get_result()->num_rows > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['service_name'] ?? '');
  $rate = (int) $_POST['rate_per_day'];
  $id   = isset($_POST['service_id']) ? (int) $_POST['service_id'] : null;

  if (isset($_POST['add_service']) && $name && $rate) {
    if (!serviceExists($conn, $name)) {
      $stmt = $conn->prepare("INSERT INTO studio_services (service_name, rate_per_day) VALUES (?, ?)");
      $stmt->bind_param("si", $name, $rate);
      $stmt->execute();
    } else {
      $_SESSION['error'] = "Service already exists.";
    }
  }

  if (isset($_POST['edit_service']) && $name && $rate && $id) {
    if (!serviceExists($conn, $name, $id)) {
      $stmt = $conn->prepare("UPDATE studio_services SET service_name = ?, rate_per_day = ? WHERE id = ?");
      $stmt->bind_param("sii", $name, $rate, $id);
      $stmt->execute();
    } else {
      $_SESSION['error'] = "Service already exists.";
    }
  }

  if (isset($_POST['delete_service']) && $id) {
    $conn->query("DELETE FROM studio_services WHERE id = $id");
  }

  header("Location: admin.php#services");
  exit;
}

$result = $conn->query("SELECT * FROM studio_services ORDER BY service_name ASC");
?>

<div id="updateNotice" class="alert alert-info alert-sm text-center" style="display: none;">
  You've made changes. Click <strong>Update</strong> to save.
</div>



<h4 class="mt-4">Manage Services</h4>

<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php endif; ?>

<form class="form-inline mb-3" method="POST">
  <input type="text" name="service_name" class="form-control mr-2" placeholder="Service Name" required>
  <input type="number" name="rate_per_day" class="form-control mr-2" placeholder="Rate per Day" required>
  <button type="submit" name="add_service" class="btn btn-success">Add Service</button>
</form>

<table class="table table-bordered table-sm">
  <thead class="thead-light">
    <tr>
      <th>Service</th>
      <th>Rate per Day</th>
      <th style="width:150px">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <form method="POST">
          <td>
            <input type="text" name="service_name" value="<?= htmlspecialchars($row['service_name']) ?>" class="form-control" required>
          </td>
          <td>
            <input type="number" name="rate_per_day" value="<?= $row['rate_per_day'] ?>" class="form-control" required>
          </td>
          <td>
            <input type="hidden" name="service_id" value="<?= $row['id'] ?>">
            <button name="edit_service" class="btn btn-sm btn-primary">Update</button>
            <button name="delete_service" class="btn btn-sm btn-danger" onclick="return confirm('Delete this service?')">Delete</button>
          </td>
        </form>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('input[name="service_name"], input[name="rate_per_day"]').forEach(input => {
      input.addEventListener("input", () => {
        document.getElementById("updateNotice").style.display = "block";
      });
    });
  });
</script>
