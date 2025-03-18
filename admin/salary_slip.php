<?php
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Load PHPMailer

header("Content-Type: text/html"); // Ensure HTML is rendered

// Unique Payroll Company Name
$payroll_company = "SmartPay Payroll Solutions";

// Handle email sending request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['send_email'])) {
    header("Content-Type: application/json"); // Switch to JSON for email responses

    $emp_code = $_POST['emp_code'] ?? '';
    $email = $_POST['email'] ?? '';
    $pay_amount = $_POST['pay_amount'] ?? '';
    $earnings = $_POST['earnings'] ?? '';
    $deductions = $_POST['deductions'] ?? '';
    $net_salary = $_POST['net_salary'] ?? '';
    $generate_date = $_POST['generate_date'] ?? '';
    $earning_type = $_POST['earning_type'] ?? '';
    $deduction_type = $_POST['deduction_type'] ?? '';

    if (empty($email)) {
        echo json_encode(["status" => "error", "message" => "Email not found"]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kalyani.mv005@gmail.com'; // Your email
        $mail->Password = 'pjcl kkmo eujo wwyr'; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email details
        $mail->setFrom('kalyani.mv005@gmail.com', 'HR Department');
        $mail->addAddress($email);
        $mail->Subject = "Salary Slip for $generate_date";
        $mail->isHTML(true);
        $mail->Body = "
            <h2 style='text-align: center;'>$payroll_company</h2>
            <p style='text-align: center;'><strong>Pay Slip - $generate_date</strong></p>
            <table border='1' cellpadding='8' cellspacing='0' style='width:100%; border-collapse: collapse;'>
                <tr style='background-color: #f2f2f2;'>
                    <th>Employee Code</th><td>$emp_code</td>
                </tr>
                <tr>
                    <th>Pay Amount</th><td>₹$pay_amount</td>
                </tr>
                <tr>
                    <th>Earnings</th><td>₹$earnings</td>
                </tr>
                <tr>
                    <th>Deductions</th><td>₹$deductions</td>
                </tr>
                <tr style='background-color: #f2f2f2;'>
                    <th>Net Salary</th><td><strong>₹$net_salary</strong></td>
                </tr>
                <tr>
                    <th>Earning Type</th><td>$earning_type</td>
                </tr>
                <tr>
                    <th>Deduction Type</th><td>$deduction_type</td>
                </tr>
            </table>
            <p>Thank you,</p>
            <p><strong>HR Department | $payroll_company</strong></p>
        ";

        // Send email
        if ($mail->send()) {
            echo json_encode(["status" => "success", "message" => "Payslip sent to $email"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to send email"]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Mailer Error: " . $mail->ErrorInfo]);
    }
    exit;
}

// Fetch salary details for table display
$sql = "SELECT s.salary_id, s.emp_code, s.pay_amount, s.earning_total, s.deduction_total, 
        s.net_salary, s.generate_date, s.earning_type, s.deduction_type, e.email, e.mobile_number 
        FROM salary_table s 
        JOIN employees e ON s.emp_code = e.emp_code";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Details</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        button { cursor: pointer; padding: 5px 10px; margin: 2px; border: none; }
        .email-btn { background-color: #007bff; color: white; }
        .whatsapp-btn { background-color: #25D366; color: white; }
    </style>
</head>
<body>

<h2 style="text-align: center;"><?php echo $payroll_company; ?></h2>

<table>
    <thead>
        <tr>
            <th>Emp Code</th>
            <th>Pay Amount (₹)</th>
            <th>Earnings (₹)</th>
            <th>Deductions (₹)</th>
            <th>Net Salary (₹)</th>
            <th>Generate Date</th>
            <th>Earning Type</th>
            <th>Deduction Type</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['emp_code']) ?></td>
            <td><?= number_format($row['pay_amount'], 2) ?></td>
            <td><?= number_format($row['earning_total'], 2) ?></td>
            <td><?= number_format($row['deduction_total'], 2) ?></td>
            <td><strong><?= number_format($row['net_salary'], 2) ?></strong></td>
            <td><?= htmlspecialchars($row['generate_date']) ?></td>
            <td><?= htmlspecialchars($row['earning_type']) ?></td>
            <td><?= htmlspecialchars($row['deduction_type']) ?></td>
            <td>
                <button class="email-btn" onclick="sendEmail('<?= $row['emp_code'] ?>', '<?= $row['email'] ?>', '<?= $row['pay_amount'] ?>', '<?= $row['earning_total'] ?>', '<?= $row['deduction_total'] ?>', '<?= $row['net_salary'] ?>', '<?= $row['generate_date'] ?>', '<?= $row['earning_type'] ?>', '<?= $row['deduction_type'] ?>')">
                    📧 Email
                </button>
                <button class="whatsapp-btn" onclick="sendWhatsApp('<?= $row['mobile_number'] ?>', '<?= $row['emp_code'] ?>', '<?= $row['pay_amount'] ?>', '<?= $row['net_salary'] ?>', '<?= $row['generate_date'] ?>')">
                    📱 WhatsApp
                </button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
function sendWhatsApp(mobile, empCode, payAmount, netSalary, generateDate) {
    if (!mobile) {
        alert("Employee mobile number not found!");
        return;
    }

    let message = `Hello, your salary details:\n\n`
                + `Employee Code: ${empCode}\n`
                + `Pay Amount: ₹${payAmount}\n`
                + `Net Salary: ₹${netSalary}\n`
                + `Generated Date: ${generateDate}\n\n`
                + `- HR Department, SmartPay Payroll Solutions`;

    let whatsappUrl = `https://wa.me/${mobile}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, "_blank");
}
</script>

</body>
</html>

<?php $conn->close(); ?>
