<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "payroll");
$result = $conn->query("SELECT * FROM employees");
echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
$conn->close();
?>
