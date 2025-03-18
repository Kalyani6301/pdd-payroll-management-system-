<?php

include 'config.php';

$sql = "SELECT 
            emp_code, 
            CONCAT(first_name, ' ', last_name) AS name, 
            idetity_no AS identity, 
            mobile_number AS contact, 
            dob, 
            joining_date AS joining, 
            blood_group AS bloodgroup, 
            emp_type AS employee_type,
            email 
        FROM employees";

$result = $conn->query($sql);

$employees = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}
$conn->close();
?>

<?php 

include 'header.php';
include 'topbar.php';
include 'sidebar.php';

?>

<div class="main-content">
    <div class="container mt-5">
        <h2 class="text-center mt-5 pt-5">All Employee List</h2>

        <div class="table-responsive">
            <table id="employeeTable" class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Identity</th>
                        <th>Contact</th>
                        <th>DOB</th>
                        <th>Joining Date</th>
                        <th>Blood Group</th>
                        <th>Employee Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($employees)): ?>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?= htmlspecialchars($employee['emp_code']) ?></td>
                                <td><?= htmlspecialchars($employee['name']) ?></td>
                                <td><?= htmlspecialchars($employee['email']) ?></td>
                                <td><?= htmlspecialchars($employee['identity']) ?></td>
                                <td><?= htmlspecialchars($employee['contact']) ?></td>
                                <td><?= htmlspecialchars($employee['dob']) ?></td>
                                <td><?= htmlspecialchars($employee['joining']) ?></td>
                                <td><?= htmlspecialchars($employee['bloodgroup']) ?></td>
                                <td><?= htmlspecialchars($employee['employee_type']) ?></td>
                                <td>
                                    <button class="salary-btn btn btn-primary" onclick="redirectToSalary('<?= addslashes($employee['emp_code']) ?>')">Salary</button>
                        
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No employees found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#employeeTable').DataTable({
            "paging": true,        // Enables Previous/Next Pagination
            "searching": true,     // Enables Search Bar
            "lengthMenu": [5, 10, 25, 50], // Show entries dropdown
            "pageLength": 5        // Default to 5 entries per page
        });
    });
</script>

<?php include 'footer.php'; ?>
