<?php 

include 'config.php';

$emp_code = $_SESSION['emp_code'];

$sql = "SELECT * FROM employees WHERE emp_code = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("s", $emp_code);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

?>

<nav class="navbar navbar-expand-lg navbar-light bg-primary text-white" style="background:rgb(6, 167, 236) !important; width:80%;margin-left:280px;">
    
    <div class="container-fluid">
        <span class="navbar-brand text-white">Payroll Management System</span>
        <div class="dropdown">
            <button class="btn btn-primary text-white dropdown-toggle" type="button" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                <li><a class="dropdown-item" href="../user/emp_dashboard.php">View Profile</a></li>
                <li><a class="dropdown-item" href="../user/password.php">Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="../user/emp_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>