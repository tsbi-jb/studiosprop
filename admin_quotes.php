<?php
include 'includes/db.php';

$search = trim($_GET['search'] ?? '');
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Build where clause for search
$where = '';
if ($search !== '') {
  $like = "%" . $conn->real_escape_string($search) . "%";
  $where = "WHERE quote_id LIKE '$like' OR client_name LIKE '$like' OR project_title LIKE '$like' OR your_email LIKE '$like'";
}

// Count total
$count_sql = "SELECT COUNT(*) as total FROM studio_quotes $where";
$total_result = $conn->query($count_sql);
$total_quotes = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_quotes / $limit);

// Fetch filtered paginated results
$sql = "SELECT * FROM studio_quotes $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!-- Search Bar -->
<form method="GET" action="" class="form-inline mb-3">
  <input type="text" name="search" class="form-control mr-2" placeholder="Search Quote ID, Client, Project, Email" value="<?= htmlspecialchars($search) ?>">
  <button type="submit" class="btn btn-primary">Search</button>
</form>

<!-- Table -->
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Quote ID</th>
      <th>Client</th>
      <th>Email</th>
      <th>Project</th>
      <th>Shoot Dates</th>
      <th>Total</th>
      <th>Download</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['quote_id']) ?></td>
          <td><?= htmlspecialchars($row['client_name']) ?></td>
          <td><?= htmlspecialchars($row['your_email']) ?></td>
          <td><?= htmlspecialchars($row['project_title']) ?></td>
          <td><?= htmlspecialchars($row['shoot_dates']) ?></td>
          <td>₹<?= number_format($row['total']) ?></td>
          <td>
            <a href="download_quote.php?quote_id=<?= urlencode($row['quote_id']) ?>" class="btn btn-sm btn-primary" target="_blank">Download</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7" class="text-center text-muted">No results found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>


<!-- Pagination -->
<?php if ($total_pages > 1): ?>
<nav>
  <ul class="pagination justify-content-center">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>#quotes"><?= $i ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>
<?php endif; ?>
