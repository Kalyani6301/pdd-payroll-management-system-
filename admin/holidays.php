<?php include 'header.php'; ?>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">

        <?php include 'topbar.php'; ?>

        <div class="container mt-5">
            <div id="alertContainer"></div> <!-- Alert Message Container -->
            <h2 class="d-block m-auto pt-5">Holiday Management</h2>
            <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#holidayModal" onclick="clearForm()">Add Holiday</button>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="holidayTable"></tbody>
            </table>
        </div>

        <!-- Holiday Modal -->
        <div class="modal fade" id="holidayModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Manage Holiday</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="holiday_id">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="holiday_title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="holiday_desc"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="holiday_date">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" id="holiday_type">
                                <option value="Compulsory">Compulsory</option>
                                <option value="Restricted">Restricted</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" onclick="saveHoliday()">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include "footer.php"; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    loadHolidays();
});

function loadHolidays() {
    $.ajax({
        url: '../api/holidays.php?action=fetch',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            let rows = '';
            data.forEach(holiday => {
                rows += `<tr>
                    <td>${holiday.holiday_id}</td>
                    <td>${holiday.holiday_title}</td>
                    <td>${holiday.holiday_desc}</td>
                    <td>${holiday.holiday_date}</td>
                    <td>${holiday.holiday_type}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editHoliday(${holiday.holiday_id})">✏ Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteHoliday(${holiday.holiday_id})">🗑 Delete</button>
                    </td>
                </tr>`;
            });
            $('#holidayTable').html(rows);
        }
    });
}

function clearForm() {
    $('#holiday_id').val('');
    $('#holiday_title').val('');
    $('#holiday_desc').val('');
    $('#holiday_date').val('');
    $('#holiday_type').val('Compulsory');
}

function editHoliday(holiday_id) {
    $.ajax({
        url: '../api/holidays.php?action=get_holiday&holiday_id=' + holiday_id,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#holiday_id').val(response.data.holiday_id);
                $('#holiday_title').val(response.data.holiday_title);
                $('#holiday_desc').val(response.data.holiday_desc);
                $('#holiday_date').val(response.data.holiday_date);
                $('#holiday_type').val(response.data.holiday_type);
                $('#holidayModal').modal('show');
            } else {
                alert(response.message);
            }
        }
    });
}

function saveHoliday() {
    let holiday_id = $('#holiday_id').val();
    let holiday_title = $('#holiday_title').val();
    let holiday_desc = $('#holiday_desc').val();
    let holiday_date = $('#holiday_date').val();
    let holiday_type = $('#holiday_type').val();

    if (!holiday_title || !holiday_desc || !holiday_date || !holiday_type) {
        alert("All fields are required!");
        return;
    }

    $.ajax({
        url: '../api/holidays.php?action=' + (holiday_id ? 'update' : 'save'),
        method: 'POST',
        data: { holiday_id, holiday_title, holiday_desc, holiday_date, holiday_type },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#holidayModal').modal('hide');  // Hide modal
                $('body').removeClass('modal-open'); // Remove any overlay effect
                $('.modal-backdrop').remove(); // Clear any leftover backdrops
                
                showSuccessMessage("Holiday " + (holiday_id ? "updated" : "added") + " successfully!");
                loadHolidays(); // Refresh the holiday list

            } else {
                alert(response.message);
            }
        }
    });
}


function deleteHoliday(holiday_id) {
    if (confirm("Are you sure you want to delete this holiday?")) {
        $.ajax({
            url: '../api/holidays.php?action=delete',
            method: 'POST',
            data: { holiday_id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadHolidays();
                    showSuccessMessage("Holiday deleted successfully!");
                } else {
                    alert(response.message);
                }
            }
        });
    }
}

function showSuccessMessage(message) {
    let successAlert = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;
    $("#alertContainer").html(successAlert);
    
    setTimeout(() => {
        $(".alert").alert('close');
    }, 3000);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
