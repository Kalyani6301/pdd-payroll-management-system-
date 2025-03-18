<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Highcharts.chart('chart-container', {
            chart: { type: 'column' },
            title: { text: 'Company Statistics' },
            xAxis: { categories: ['Employees', 'Leave Requests', 'Holidays'] },
            yAxis: { title: { text: 'Count' } },
            series: [{
                name: 'Count',
                data: [<?php echo $employeeCount; ?>, <?php echo $leaveCount; ?>, <?php echo $holidayCount; ?>]
            }]
        });
    });
</script>

<script>
    
function redirectToSalary(employeeId) {
    window.location.href = `add_salary.php?employee_id=${employeeId}`;
}

function redirectToPayhead(employeeId) {
    window.location.href = `pay_structure.php?employee_id=${employeeId}`;
}

</script>
<script>
async function updateLeaveStatus(leaveId, status) {
    if (!confirm(`Are you sure you want to mark this leave as ${status}?`)) return;

    let approveBtn = document.querySelector(`#row-${leaveId} .btn-success`);
    let rejectBtn = document.querySelector(`#row-${leaveId} .btn-danger`);

    // Show loading state
    approveBtn.disabled = true;
    rejectBtn.disabled = true;
    approveBtn.textContent = "Processing...";
    rejectBtn.textContent = "Processing...";

    try {
        const response = await axios.post('api/leave_management.php', new URLSearchParams({ 
            leave_id: leaveId, 
            status, 
            action: 'update_status' 
        }));

        if (response.data.status === "success") {
            alert(response.data.message);
            document.getElementById(`status-${leaveId}`).textContent = status;
            document.getElementById(`status-${leaveId}`).className = `badge ${status === 'Approved' ? 'bg-success' : 'bg-danger'}`;
        } else {
            alert("Error: " + (response.data.message || "Failed to update status"));
            approveBtn.disabled = false;
            rejectBtn.disabled = false;
        }
    } catch (error) {
        console.error("Error updating leave status:", error);
        alert("An error occurred while updating the leave status.");
        approveBtn.disabled = false;
        rejectBtn.disabled = false;
    } finally {
        approveBtn.textContent = "Approve";
        rejectBtn.textContent = "Reject";
    }
}


</script>
</body>
</html>
