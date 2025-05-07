<?php
require 'includes/db.php';
require 'dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

ini_set('display_errors', 1);
error_reporting(E_ALL);

$quote_id = $_GET['quote_id'] ?? '';
if (!$quote_id) {
  die("Invalid quote ID");
}

// Fetch quote details
$stmt = $conn->prepare("SELECT * FROM studio_quotes WHERE quote_id = ?");
$stmt->bind_param("s", $quote_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  die("Quote not found.");
}

$quote = $result->fetch_assoc();

// Format category if needed
$category = $quote['category'] ?? '';
$category_json = json_decode($category, true);
if (is_array($category_json)) {
  $category = implode(', ', $category_json);
}

// Services list parsing
$services_str = $quote['services'] ?? '';
$services = [];
foreach (explode(',', $services_str) as $line) {
  $line = trim($line);
  if (preg_match('/^(.*?) – ₹(\d+)$/u', $line, $match)) {
    $services[] = [
      'service_name' => $match[1],
      'rate_per_day' => 0,
      'days' => 0,
      'total' => (int)$match[2],
    ];
  }
}

// PDF generation
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);

$logo_path = 'assets/logo.png';
$logo_base64 = '';
if (file_exists($logo_path)) {
  $type = pathinfo($logo_path, PATHINFO_EXTENSION);
  $data = file_get_contents($logo_path);
  $logo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
}

$service_rows = '';
$total_amount = 0;
foreach ($services as $s) {
  $name  = htmlspecialchars($s['service_name']);
  $cost  = number_format($s['total']);
  $total_amount += $s['total'];
  $service_rows .= "<tr><td colspan='3'>$name</td><td>₹$cost</td></tr>";
}

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; padding: 20px; }
    .logo { height: 60px; margin-bottom: 20px; }
    .header { text-align: center; margin-bottom: 30px; }
    .section { margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #444; padding: 8px; text-align: left; }
    th { background-color: #f0f0f0; }
    .footer { margin-top: 40px; font-size: 12px; color: #555; }
  </style>
</head>
<body>
  <div class="header">
    <?php if (!empty($logo_base64)): ?>
      <img src="<?= $logo_base64 ?>" class="logo" alt="Company Logo">
    <?php endif; ?>
    <h2>Studio Service Proposal</h2>
  </div>

  <div class="section">
    <p><strong>Quote ID:</strong> <?= htmlspecialchars($quote['quote_id']) ?></p>
    <p><strong>Client:</strong> <?= htmlspecialchars($quote['client_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($quote['your_email'] ?? '') ?></p>
    <p><strong>Project:</strong> <?= htmlspecialchars($quote['project_title']) ?></p>
    <p><strong>Shoot Dates:</strong> <?= htmlspecialchars($quote['shoot_dates']) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($category) ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($quote['location']) ?></p>
  </div>

  <div class="section">
    <h4>Budget Overview</h4>
    <table>
      <thead>
        <tr>
          <th colspan="3">Item</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?= $service_rows ?>
        <tr>
          <td colspan="3" style="text-align:right;"><strong>Total</strong></td>
          <td><strong>₹<?= number_format($total_amount) ?></strong></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="footer">
    <p><strong>Thank you,</strong><br>
    Studios Team<br>The Small Big Idea</p>
    <p><em><strong>Disclaimer:</strong> This is a preliminary proposal. Contact Studios Head for confirmation.</em></p>
  </div>
</body>
</html>
<?php
$html = ob_get_clean();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();      
$dompdf->stream("{$quote['quote_id']}.pdf", ["Attachment" => true]);
exit;
