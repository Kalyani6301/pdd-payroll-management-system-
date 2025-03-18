<?php
session_start();
include('conf/config.php');
include('conf/check_login.php');

if (!isset($_SESSION['emp_code'])) {
    header("Location: employee_login.php");
    exit();
}

$emp_code = $_SESSION['emp_code'];

// Fetch employee details
$query = "SELECT emp_password FROM employees WHERE emp_code = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $emp_code);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

if (!$employee) {
    die("Employee not found!");
}

// Handle password change request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Verify old password (without hashing)
    if ($old_password !== $employee['emp_password']) {
        echo "<script>alert('Old password is incorrect!');</script>";
    } elseif ($new_password !== $confirm_new_password) {
        echo "<script>alert('New passwords do not match!');</script>";
    } else {
        // Update the new password in the database (without hashing)
        $update_query = "UPDATE employees SET emp_password = ? WHERE emp_code = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $new_password, $emp_code);

        if ($stmt->execute()) {
            echo "<script>alert('Password changed successfully!'); window.location.href='../user/emp_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating password.');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .container {
            width: 40%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Change Password</h2>
    <form method="POST">
        <label>Old Password:</label>
        <input type="password" name="old_password" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <label>Confirm New Password:</label>
        <input type="password" name="confirm_new_password" required>

        <button type="submit" name="change_password">Change Password</button>
    </form>
</div>

</body>
</html>
