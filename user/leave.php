<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('conf/config.php'); 
include('conf/check_login.php');

if (!isset($_SESSION['emp_code'])) {
    header("Location: ../user/emp_login.php");
    exit();
}

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
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_subject = $_POST['leave_subject'];
    $leave_dates = $_POST['leave_dates'];
    $leave_message = $_POST['leave_message'];
    $leave_type = $_POST['leave_type'];
    $leave_status = 'Pending';
    $apply_date = date('Y-m-d');

    $insert_sql = "INSERT INTO leave_request (emp_code, leave_subject, leave_dates, leave_message, leave_type, leave_status, apply_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssssss", $emp_code, $leave_subject, $leave_dates, $leave_message, $leave_type, $leave_status, $apply_date);
    
    if ($stmt->execute()) {
        echo "<script>alert('Leave request submitted successfully!'); window.location.href='leave.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error submitting leave request.');</script>";
    }
    $stmt->close();
}

$leave_sql = "SELECT leave_subject, leave_dates, leave_message, leave_type, leave_status FROM leave_request WHERE emp_code = ?";
$stmt = $conn->prepare($leave_sql);
$stmt->bind_param("s", $emp_code);
$stmt->execute();
$leave_result = $stmt->get_result();
?>

<?php
include 'header.php';
include 'topbar.php';
?>

<div class="d-flex">
<?php include 'sidebar.php';?>

<div class="container-fluid dashboard-container">
    <div class="content">
        <h2>Apply for Leave</h2>
        <form method="POST">
            <label>Leave Subject</label>
            <input type="text" name="leave_subject" required class="form-control">

            <label>Leave Dates</label>
            <input type="text" name="leave_dates" required class="form-control">

            <label>Leave Message</label>
            <textarea name="leave_message" required class="form-control"></textarea>

            <label>Leave Type</label>
            <select name="leave_type" required class="form-control">
                <option value="" disabled selected>Select Leave Type</option>
                <option value="Casual Leave">Casual Leave</option>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Paid Leave">Paid Leave</option>
                <option value="Maternity Leave">Maternity Leave</option>
                <option value="Paternity Leave">Paternity Leave</option>
                <option value="Bereavement Leave">Bereavement Leave</option>
                <option value="Unpaid Leave">Unpaid Leave</option>
                <option value="Compensatory Leave">Compensatory Leave</option>
                <option value="Half-day Leave">Half-day Leave</option>
                <option value="Study Leave">Study Leave</option>
            </select>
            
            <br>
            <button type="submit" class="btn btn-primary">Apply for Leave</button>
        </form>

        <h2 class="mt-4">My Leaves</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Dates</th>
                    <th>Message</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($leave = $leave_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($leave['leave_subject']); ?></td>
                    <td><?php echo htmlspecialchars($leave['leave_dates']); ?></td>
                    <td><?php echo htmlspecialchars($leave['leave_message']); ?></td>
                    <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                    <td><?php echo htmlspecialchars($leave['leave_status']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>

<?php include 'footer.php'; ?>
