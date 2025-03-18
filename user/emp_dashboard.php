<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('conf/config.php'); 
include('conf/check_login.php');

if (!isset($_SESSION['emp_code'])) {
    header("Location: ../user/login.php");
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
    $update_sql = "UPDATE employees SET first_name = ?, last_name = ?, dob = ?, gender = ?, marital_status = ?, religion = ?, address = ?, city = ?, state = ?, country = ?, email = ?, mobile_number = ?, telephone = ?, identity_doc = ?, idetity_no = ?, emp_type = ?, joining_date = ?, blood_group = ?, photo = ?, designation = ?, department = ?, pan_no = ?, bank_name = ?, account_no = ?, ifsc_code = ?, pf_code = ? WHERE emp_code = ?";

    $stmt = $conn->prepare($update_sql);
    if (!$stmt) {
        die("Update SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssssssssssssssss", 
        $_POST['first_name'], $_POST['last_name'], $_POST['dob'], $_POST['gender'],
        $_POST['marital_status'], $_POST['religion'], $_POST['address'], $_POST['city'], 
        $_POST['state'], $_POST['country'], $_POST['email'], $_POST['mobile_number'], 
        $_POST['telephone'], $_POST['identity_doc'], $_POST['idetity_no'], $_POST['emp_type'], 
        $_POST['joining_date'], $_POST['blood_group'], $_POST['photo'], $_POST['designation'], 
        $_POST['department'], $_POST['pan_no'], $_POST['bank_name'], $_POST['account_no'], 
        $_POST['ifsc_code'], $_POST['pf_code'], $emp_code
    );

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='emp_dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
    $stmt->close();
}
?>
 <?php
  include 'header.php';?>
<?php
  include 'topbar.php';
  include 'content.php';
?>



<?php  include 'footer.php';?>

