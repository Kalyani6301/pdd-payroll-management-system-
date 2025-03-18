<?php
include 'header.php';
include 'config.php'; // Ensure you have a DB connection file

// Fetch counts from the database
$employeeCount = $conn->query("SELECT COUNT(*) as count FROM employees")->fetch_assoc()['count'];
$leaveCount = $conn->query("SELECT COUNT(*) as count FROM leave_request")->fetch_assoc()['count'];
$holidayCount = $conn->query("SELECT COUNT(*) as count FROM holidays")->fetch_assoc()['count'];
?>

<style>
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        width: 100%;
    }
    .widgets {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-bottom: 20px;
        width: 100%;
    }
    .widget {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        max-width: 300px;
    }
    .widget h3 {
        margin: 0;
        font-size: 18px;
        color: #333;
    }
    .widget p {
        font-size: 24px;
        font-weight: bold;
        margin: 5px 0 0;
        color: #007bff;
    }
    .widget i {
        font-size: 40px;
        color: #007bff;
        margin-bottom: 10px;
    }
    #chart-container {
        width: 100%;
        max-width: 1200px;
        height: 400px;
        margin-top: 20px;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <?php include 'topbar.php'; ?>

    <div class="main-content">
        <div class="container mt-5">
            <div class="widgets">
                <div class="widget">
                    <i class="fas fa-users"></i>
                    <h3>Total Employees</h3>
                    <p><?php echo $employeeCount; ?></p>
                </div>
                <div class="widget">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Total Leave Requests</h3>
                    <p><?php echo $leaveCount; ?></p>
                </div>
                <div class="widget">
                    <i class="fas fa-gift"></i>
                    <h3>Total Holidays</h3>
                    <p><?php echo $holidayCount; ?></p>
                </div>
            </div>

            <div id="chart-container"></div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>