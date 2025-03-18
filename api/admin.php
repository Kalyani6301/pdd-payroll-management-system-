<?php
header('Content-Type: application/json');
define('DB_HOST', 'localhost'); // Change if needed
define('DB_USER', 'root');      // Database username
define('DB_PASS', '');          // Database password
define('DB_NAME', 'payroll'); // Database name

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Register Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->bind_param("ss", $username, $password);
    echo $stmt->execute() ? json_encode(['message' => 'User registered successfully']) : json_encode(['error' => 'Registration failed']);
}

// Login User
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        echo password_verify($password, $hashed_password) ? json_encode(['message' => 'Login successful', 'user_id' => $id]) : json_encode(['error' => 'Invalid credentials']);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}

// Add Employee
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    
    $stmt = $conn->prepare("INSERT INTO employees (name, position, salary) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $position, $salary);
    echo $stmt->execute() ? json_encode(['message' => 'Employee added successfully']) : json_encode(['error' => 'Failed to add employee']);
}

// Generate Payroll
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_payroll'])) {
    $employee_id = $_POST['employee_id'];
    $amount = $_POST['amount'];
    
    $stmt = $conn->prepare("INSERT INTO payroll (employee_id, amount, pay_date, status) VALUES (?, ?, NOW(), 'pending')");
    $stmt->bind_param("id", $employee_id, $amount);
    echo $stmt->execute() ? json_encode(['message' => 'Payroll processed']) : json_encode(['error' => 'Payroll processing failed']);
}

// Fetch Payroll Records
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['payroll'])) {
    $result = $conn->query("SELECT p.id, e.name, p.amount, p.pay_date, p.status FROM payroll p JOIN employees e ON p.employee_id = e.id");
    $payrolls = [];
    while ($row = $result->fetch_assoc()) {
        $payrolls[] = $row;
    }
    echo json_encode($payrolls);
}

$conn->close();
?>
