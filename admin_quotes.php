<?php
// ---------- admin_quotes.php ----------
$result = $conn->query("SELECT * FROM studio_quotes ORDER BY created_at DESC");
?>

<h4 class="mt-4">Generated Quotes</h4>
<table class="table table-bordered table-sm">
  <thead class="thead-light">
    <tr>
      <th>Quote ID</th>
      <th>Client</th>
      <th>Email</th>
      <th>Project</th>
      <th>Dates</th>
      <th>Total</th>
      <th>Created At</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($q = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($q['quote_id']) ?></td>
        <td><?= htmlspecialchars($q['client_name']) ?></td>
        <td><?= htmlspecialchars($q['your_email']) ?></td>
        <td><?= htmlspecialchars($q['project_title']) ?></td>
        <td><?= htmlspecialchars($q['shoot_dates']) ?></td>
        <td>₹<?= number_format($q['total']) ?></td>
        <td><?= htmlspecialchars($q['created_at']) ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
