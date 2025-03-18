<?php
include 'config.php';

// Fetch leaves from database
$conn = new mysqli("localhost", "root", "", "payroll");

if ($conn->connect_error) {
    die("Database connection failed");
}

$sql = "SELECT * FROM leave_request";
$result = $conn->query($sql);
$leaves = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Leave Requests</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>EMP CODE</th>
                    <th>SUBJECT</th>
                    <th>DATES</th>
                    <th>MESSAGE</th>
                    <th>TYPE</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaves as $index => $leave) { ?>
                    <tr id="row-<?php echo $leave['leave_id']; ?>">
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $leave['emp_code']; ?></td>
                        <td><?php echo $leave['leave_subject']; ?></td>
                        <td><?php echo $leave['leave_dates']; ?></td>
                        <td><?php echo $leave['leave_message']; ?></td>
                        <td><?php echo $leave['leave_type']; ?></td>
                        <td>
                            <span id="status-<?php echo $leave['leave_id']; ?>" class="badge 
                                <?php echo $leave['leave_status'] === 'Approved' ? 'bg-success' : 
                                    ($leave['leave_status'] === 'Rejected' ? 'bg-danger' : 'bg-warning'); ?>">
                                <?php echo $leave['leave_status'] ?: 'Pending'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="updateLeaveStatus(<?php echo $leave['leave_id']; ?>, 'Approved')"
                                <?php echo ($leave['leave_status'] === 'Approved' || $leave['leave_status'] === 'Rejected') ? 'disabled' : ''; ?>>
                                Approve
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="updateLeaveStatus(<?php echo $leave['leave_id']; ?>, 'Rejected')"
                                <?php echo ($leave['leave_status'] === 'Approved' || $leave['leave_status'] === 'Rejected') ? 'disabled' : ''; ?>>
                                Reject
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        async function updateLeaveStatus(leaveId, status) {
            if (!confirm(`Are you sure you want to mark this leave as ${status}?`)) return;

            try {
                const response = await axios.post('../api/leave_management.php', new URLSearchParams({ 
                    leave_id: leaveId, 
                    status, 
                    action: 'update_status' 
                }));

                if (response.data.status === "success") {
                    alert(response.data.message);
                    // Update status badge without reloading
                    document.getElementById(`status-${leaveId}`).textContent = status;
                    document.getElementById(`status-${leaveId}`).className = `badge ${status === 'Approved' ? 'bg-success' : 'bg-danger'}`;
                    // Disable buttons after approval/rejection
                    document.querySelector(`#row-${leaveId} .btn-success`).disabled = true;
                    document.querySelector(`#row-${leaveId} .btn-danger`).disabled = true;
                } else {
                    alert("Error: " + (response.data.message || "Failed to update status"));
                }
            } catch (error) {
                console.error("Error updating leave status:", error);
                alert("An error occurred while updating the leave status.");
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
