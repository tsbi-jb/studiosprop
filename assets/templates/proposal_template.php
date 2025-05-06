<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 13px;
      padding: 20px;
      color: #222;
    }
    .logo {
      height: 60px;
      margin-bottom: 20px;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    .section {
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #444;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f0f0f0;
    }
    .footer {
      margin-top: 40px;
      font-size: 12px;
      color: #555;
    }
    .bold {
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="header">
  <?php if (!empty($logo_base64)): ?>
    <img src="<?= $logo_base64 ?>" class="logo" alt="Company Logo">
  <?php endif; ?>
  <h2>Studio Service Proposal1</h2>
</div>

<div class="section">
  <p><span class="bold">Client:</span> <?= htmlspecialchars($client_name) ?></p>
  <p><span class="bold">Project:</span> <?= htmlspecialchars($project_title) ?></p>
  <p><span class="bold">Shoot Dates:</span> <?= htmlspecialchars($shoot_dates) ?></p>
  <p><span class="bold">Duration:</span> <?= htmlspecialchars($duration) ?></p>
</div>

<input type="hidden" name="summary_html" value="<?= htmlspecialchars("<ul>{$breakdown}</ul>") ?>">

<div class="section">
  <h4>Budget Overview</h4>
  <table>
    <thead>
      <tr>
        <th>Item</th>
        <th>Days</th>
        <th>Total Cost</th>
      </tr>
    </thead>
    <tbody>
      <?= $service_rows ?>
      <tr>
        <td colspan="2" style="text-align:right;"><strong>Total</strong></td>
        <td><strong>₹<?= $total_amount ?></strong></td>
      </tr>
    </tbody>
  </table>
</div>

<div class="footer">
  <p><strong>Thank you,</strong><br>
  Studios Team<br>The Small Big Idea</p>

  <p><em><strong>Disclaimer:</strong> The details provided in this proposal form are for preliminary reference only. All values, figures, and projections mentioned herein are tentative and subject to change. A more detailed understanding of the proposal, including specific insights and justifications, is available exclusively through the Studios Head. This document does not constitute a final agreement.</em></p>
</div>

</body>
</html>
