<?php
include 'config.php';

// Fetch all holiday records
$sql = "SELECT holiday_id, holiday_title, holiday_desc, holiday_date, holiday_type FROM holidays";
$result = $conn->query($sql);
?>

<?php
include 'header.php';
?>

<div class="container mt-5">
    <h2 class="d-block m-auto pt-5">Holidays List</h2>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $badgeClass = ($row['holiday_type'] == 'compulsory') ? 'bg-success' : 'bg-danger';

                    echo "<tr>
                        <td>{$row['holiday_id']}</td>
                        <td>{$row['holiday_title']}</td>
                        <td>{$row['holiday_desc']}</td>
                        <td>{$row['holiday_date']}</td>
                        <td><span class='badge $badgeClass'>{$row['holiday_type']}</span></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No holiday records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>



<?php
// Close database connection
$conn->close();
include 'footer.php'
?>
