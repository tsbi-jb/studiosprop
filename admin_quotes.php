<?php
include 'includes/db.php';

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch paginated quotes
$result = $conn->query("SELECT * FROM studio_quotes ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$total_result = $conn->query("SELECT COUNT(*) as total FROM studio_quotes");
$total_quotes = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_quotes / $limit);
?>

<table class="table table-bordered table-striped mt-3">
  <thead>
    <tr>
      <th>Quote ID</th>
      <th>Client</th>
      <th>Project</th>
      <th>Shoot Dates</th>
      <th>Total</th>
      <th>Download</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['quote_id']) ?></td>
        <td><?= htmlspecialchars($row['client_name']) ?></td>
        <td><?= htmlspecialchars($row['project_title']) ?></td>
        <td><?= htmlspecialchars($row['shoot_dates']) ?></td>
        <td>₹<?= number_format($row['total']) ?></td>
        <td>
          <form action="generate_pdf.php" method="POST" target="_blank">
            <input type="hidden" name="quote_id" value="<?= htmlspecialchars($row['quote_id']) ?>">
            <input type="hidden" name="client_name" value="<?= htmlspecialchars($row['client_name']) ?>">
            <input type="hidden" name="project_title" value="<?= htmlspecialchars($row['project_title']) ?>">
            <input type="hidden" name="shoot_dates" value="<?= htmlspecialchars($row['shoot_dates']) ?>">
            <input type="hidden" name="duration" value="<?= htmlspecialchars($row['duration'] ?? 1) ?>">
            <input type="hidden" name="services_json" value='<?= htmlspecialchars(json_encode($row['services']), ENT_QUOTES, 'UTF-8') ?>'>
            <a href="download_quote.php?quote_id=<?= urlencode($row['quote_id']) ?>" class="btn btn-sm btn-primary" target="_blank">Download</a>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<!-- Pagination Controls -->
<nav>
  <ul class="pagination justify-content-center">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>#quotes"><?= $i ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>
