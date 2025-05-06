<?php
header('Content-Type: application/json');

// Include database connection
include 'includes/db.php';

$services = [];

$sql = "SELECT id, service_name, rate_per_day FROM studio_services ORDER BY service_name ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = [
            'id' => $row['id'],
            'service_name' => $row['service_name'],
            'rate_per_day' => (int) $row['rate_per_day']
        ];
    }
}

echo json_encode($services);
?>
