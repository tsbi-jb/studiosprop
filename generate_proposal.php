<?php
// ---------- generate_proposal.php ----------
include 'includes/db.php';

$client_name   = $_POST['client_name'] ?? '';
$client_email  = $_POST['client_email'] ?? '';
$project_title = $_POST['project_title'] ?? '';
$shoot_dates   = $_POST['shoot_dates'] ?? '';
$days          = (int) ($_POST['days'] ?? 1);
$category_arr = $_POST['category'] ?? [];
$location = $_POST['location'] ?? '';
$category_str = implode(', ', $category_arr);
$service_ids   = $_POST['services'] ?? [];

$total = 0;
$breakdown = '';
$services_array = [];

// Generate Quote ID
$quote_id = 'TSBI-Studios-' . preg_replace('/\s+/', '', ucwords($client_name)) . '-' . strtoupper(uniqid());

if ($client_name && $client_email && !empty($service_ids)) {
  foreach ($service_ids as $id) {
    $id = (int)$id;
    $result = $conn->query("SELECT service_name, rate_per_day FROM studio_services WHERE id = $id LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
      $rate = (int)$row['rate_per_day'];
      $cost = $rate * $days;
      $total += $cost;

      $services_array[] = [
        'service_name' => $row['service_name'],
        'rate_per_day' => $rate,
        'days' => $days,
        'total' => $cost
      ];

      $breakdown .= "<li>{$row['service_name']} – ₹$cost</li>";
    }
  }



  // Insert into database
  $services_str = strip_tags(str_replace(['<li>', '</li>'], ["", ", "], $breakdown));
  $stmt = $conn->prepare("INSERT INTO studio_quotes (quote_id, client_name, your_email, project_title, shoot_dates, services, total, category, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssiss", $quote_id, $client_name, $your_email, $project_title, $shoot_dates, $services_str, $total, $category_str, $location);
  $stmt->execute();
}

$services_json = htmlspecialchars(json_encode($services_array), ENT_QUOTES, 'UTF-8');

ob_start();
?>
<div style="max-width:800px; margin:40px auto; padding:30px; font-family:sans-serif; border:1px solid #ccc">
  <h2>Proposal Summary</h2>
  <p><strong>Quote ID:</strong> <?= htmlspecialchars($quote_id) ?></p>
  <p><strong>Client:</strong> <?= htmlspecialchars($client_name) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($client_email) ?></p>
  <p><strong>Project Title:</strong> <?= htmlspecialchars($project_title) ?></p>
  <p><strong>Shoot Dates:</strong> <?= htmlspecialchars($shoot_dates) ?></p>
  <p><strong>Number of Days:</strong> <?= $days ?></p>
  <p><strong>Category:</strong> <?= htmlspecialchars($category_str) ?></p>
  <p><strong>Location:</strong> <?= htmlspecialchars($location) ?></p>

  <hr>

  <h4>Services Breakdown:</h4>
  <ul><?= $breakdown ?></ul>
  <hr>
  <h4>Total Estimate: ₹<?= $total ?></h4>

  <form action="generate_pdf.php" method="POST" target="_blank">
    <input type="hidden" name="quote_id" value="<?= htmlspecialchars($quote_id) ?>">
    <input type="hidden" name="client_name" value="<?= htmlspecialchars($client_name) ?>">
    <input type="hidden" name="project_title" value="<?= htmlspecialchars($project_title) ?>">
    <input type="hidden" name="shoot_dates" value="<?= htmlspecialchars($shoot_dates) ?>">
    <input type="hidden" name="duration" value="<?= $days ?>">
    <input type="hidden" name="category" value="<?= htmlspecialchars($category_str) ?>">
  <input type="hidden" name="location" value="<?= htmlspecialchars($location) ?>">
    <input type="hidden" name="services_json" value='<?= $services_json ?>'>
    <button type="submit" class="btn btn-primary mt-3">Download PDF</button>
  </form>
</div>
<?php ob_end_flush(); ?>