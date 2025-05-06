<?php
session_start();
session_destroy();
header("Location: admin.php"); // or "admin_dashboard.php" if that's your dashboard file
exit;
