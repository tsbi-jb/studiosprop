<?php
// ---------- admin_services.php ----------
include 'includes/db.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_service'])) {
    $name = trim($_POST['service_name']);
    $rate = (int) $_POST['rate_per_day'];
    if ($name && $rate) {
      $stmt = $conn->prepare("INSERT INTO studio_services (service_name, rate_per_day) VALUES (?, ?)");
      $stmt->bind_param("si", $name, $rate);
      $stmt->execute();
    }
  } elseif (isset($_POST['edit_service'])) {
    $id = (int) $_POST['service_id'];
    $name = trim($_POST['service_name']);
    $rate = (int) $_POST['rate_per_day'];
    $stmt = $conn->prepare("UPDATE studio_services SET service_name = ?, rate_per_day = ? WHERE id = ?");
    $stmt->bind_param("sii", $name, $rate, $id);
    $stmt->execute();
  } elseif (isset($_POST['delete_service'])) {
    $id = (int) $_POST['service_id'];
    $conn->query("DELETE FROM studio_services WHERE id = $id");
  }
}

// Fetch all services
$result = $conn->query("SELECT * FROM studio_services ORDER BY service_name ASC");
?>

<h4 class="mt-4">Manage Services</h4>
<form class="form-inline mb-3" method="POST">
  <input type="text" name="service_name" class="form-control mr-2" placeholder="Service Name" required>
  <input type="number" name="rate_per_day" class="form-control mr-2" placeholder="Rate/Day" required>
  <button type="submit" name="add_service" class="btn btn-success">Add Service</button>
</form>

<table class="table table-bordered table-sm">
  <thead class="thead-light">
    <tr>
      <th>Service</th>
      <th>Rate/Day</th>
      <th style="width:150px">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <form method="POST">
          <td>
            <input type="text" name="service_name" value="<?= htmlspecialchars($row['service_name']) ?>" class="form-control">
          </td>
          <td>
            <input type="number" name="rate_per_day" value="<?= $row['rate_per_day'] ?>" class="form-control">
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