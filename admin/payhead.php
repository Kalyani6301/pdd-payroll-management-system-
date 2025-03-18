<?php
include 'config.php';
// Handle incoming requests
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action == 'fetch') {
    $result = $conn->query("SELECT * FROM payhead");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    exit;
}

if ($action == 'save' || $action == 'update') {
    $payhead_id = intval($_POST['payhead_id'] ?? 0);
    $name = htmlspecialchars(trim($_POST['payhead_name'] ?? ''));
    $desc = htmlspecialchars(trim($_POST['payhead_desc'] ?? ''));
    $type = htmlspecialchars(trim($_POST['payhead_type'] ?? ''));

    if (empty($name) || empty($desc) || empty($type)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if ($action == 'update' && $payhead_id > 0) {
        $stmt = $conn->prepare("UPDATE payhead SET payhead_name=?, payhead_desc=?, payhead_type=? WHERE payhead_id=?");
        $stmt->bind_param("sssi", $name, $desc, $type, $payhead_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO payhead (payhead_name, payhead_desc, payhead_type) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $desc, $type);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Payhead saved successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    }
    exit;
}

if ($action == 'delete') {
    $payhead_id = intval($_POST['payhead_id'] ?? 0);

    if ($payhead_id > 0) {
        $stmt = $conn->prepare("DELETE FROM payhead WHERE payhead_id=?");
        $stmt->bind_param("i", $payhead_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Payhead deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete payhead."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid Payhead ID."]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payheads Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Payheads Management</h2>
        <button class="btn btn-primary mb-3" onclick="openModal()">Add Payhead</button>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Payhead Name</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="payheadTableBody"></tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="payheadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Payhead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="payheadForm">
                        <input type="hidden" id="payheadId">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="payheadName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="payheadDesc" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" id="payheadType" required>
                                <option value="">Select Type</option>
                                <option value="Earnings">Earnings</option>
                                <option value="Deductions">Deductions</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="savePayhead()">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function fetchPayheads() {
            try {
                const response = await axios.get('payhead.php?action=fetch');
                const payheads = response.data;
                const tableBody = document.getElementById('payheadTableBody');
                tableBody.innerHTML = payheads.map((payhead, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${payhead.payhead_name}</td>
                        <td>${payhead.payhead_desc}</td>
                        <td>${payhead.payhead_type}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editPayhead(${payhead.payhead_id}, '${payhead.payhead_name}', '${payhead.payhead_desc}', '${payhead.payhead_type}')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deletePayhead(${payhead.payhead_id})">Delete</button>
                        </td>
                    </tr>`).join('');
            } catch (error) {
                console.error("Error fetching payheads:", error);
            }
        }

        function openModal() {
            document.getElementById('payheadForm').reset();
            document.getElementById('payheadId').value = '';
            new bootstrap.Modal(document.getElementById('payheadModal')).show();
        }

        function editPayhead(id, name, desc, type) {
            document.getElementById('payheadId').value = id;
            document.getElementById('payheadName').value = name;
            document.getElementById('payheadDesc').value = desc;
            document.getElementById('payheadType').value = type;
            new bootstrap.Modal(document.getElementById('payheadModal')).show();
        }

        async function savePayhead() {
            const payheadId = document.getElementById('payheadId').value;
            const formData = new URLSearchParams();
            formData.append('action', payheadId ? 'update' : 'save');
            formData.append('payhead_id', payheadId);
            formData.append('payhead_name', document.getElementById('payheadName').value);
            formData.append('payhead_desc', document.getElementById('payheadDesc').value);
            formData.append('payhead_type', document.getElementById('payheadType').value);

            try {
                const response = await axios.post('payhead.php', formData);
                if (response.data.success) {
                    alert("Payhead saved successfully!");
                    fetchPayheads();
                    bootstrap.Modal.getInstance(document.getElementById('payheadModal')).hide();
                } else {
                    alert(response.data.message || "Error saving payhead.");
                }
            } catch (error) {
                console.error("Error saving payhead:", error);
            }
        }

        async function deletePayhead(id) {
            if (!confirm("Are you sure you want to delete this payhead?")) return;
            try {
                const response = await axios.post('payhead.php', new URLSearchParams({ action: "delete", payhead_id: id }));
                if (response.data.success) {
                    alert("Payhead deleted successfully!");
                    fetchPayheads();
                } else {
                    alert(response.data.message || "Failed to delete payhead.");
                }
            } catch (error) {
                console.error("Error deleting payhead:", error);
            }
        }

        fetchPayheads();
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
