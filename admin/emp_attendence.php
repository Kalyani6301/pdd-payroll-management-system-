<?php
include 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch Attendance Data with Employee Details, Payable Days, and Leaves
$sql = "SELECT 
            a.attendence_date, 
            a.emp_code, 
            e.first_name, 
            e.last_name, 
            MIN(CASE WHEN a.action_name = 'Punch In' THEN a.action_time END) AS punch_in_time,
            MAX(CASE WHEN a.action_name = 'Punch Out' THEN a.action_time END) AS punch_out_time,
            MAX(CASE WHEN a.action_name = 'Punch In' THEN a.emp_desc END) AS punch_in_message,
            MAX(CASE WHEN a.action_name = 'Punch Out' THEN a.emp_desc END) AS punch_out_message,
            TIMEDIFF(
                MAX(CASE WHEN a.action_name = 'Punch Out' THEN a.action_time END), 
                MIN(CASE WHEN a.action_name = 'Punch In' THEN a.action_time END)
            ) AS work_hours,
            COUNT(DISTINCT a.attendence_date) AS payable_days,
            (DAY(LAST_DAY(CURDATE())) - COUNT(DISTINCT a.attendence_date)) AS leaves
        FROM emp_attendence a 
        INNER JOIN employees e ON a.emp_code = e.emp_code
        WHERE MONTH(a.attendence_date) = MONTH(CURDATE()) 
        AND YEAR(a.attendence_date) = YEAR(CURDATE())
        GROUP BY a.emp_code
        ORDER BY a.attendence_date DESC";

$result = $conn->query($sql);

// Store Data in an Array
$attendances = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendances[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 40px;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center">Employee Attendance</h2>
        <div class="table-responsive">
            <table id="attendanceTable" class="table table-bordered table-striped mt-4">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Emp Code</th>
                        <th>Name</th>
                        <th>Punch-In</th>
                        <th>Punch-In Message</th>
                        <th>Punch-Out</th>
                        <th>Punch-Out Message</th>
                        <th>Work Hours</th>
                        <th>Payable Days</th>
                        <th>Leaves</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($attendances)) { ?>
                        <?php foreach ($attendances as $row) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['attendence_date']) ?></td>
                                <td><?= htmlspecialchars($row['emp_code']) ?></td>
                                <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['punch_in_time'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['punch_in_message'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['punch_out_time'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['punch_out_message'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['work_hours'] ?? '0 Hrs 0 Min') ?></td>
                                <td><?= htmlspecialchars($row['payable_days']) ?></td>
                                <td><?= htmlspecialchars($row['leaves']) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="10" class="text-center text-danger">No Attendance Records Found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#attendanceTable').DataTable();
        });
    </script>

</body>
</html>
