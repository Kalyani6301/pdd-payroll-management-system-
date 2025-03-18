<?php
include 'config.php';

// Fetch payheads from the database
$query = "SELECT payhead_id, payhead_name, payhead_desc, payhead_type FROM payhead";
$result = $conn->query($query);

$payheads = [];
while ($row = $result->fetch_assoc()) {
    $payheads[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Structure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .deduction { color: red; font-weight: bold; }
        .earning { color: green; font-weight: bold; }
        .list-box { height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; }
        .list-item { padding: 8px; cursor: pointer; border-bottom: 1px solid #ccc; display: flex; justify-content: space-between; }
        .selected-list { background-color: #f8f9fa; padding: 8px; margin-bottom: 5px; }
        .amount-input { width: 100%; }
        .selected { background-color: lightgray; }
    </style>
</head>
<body class="container mt-4">
    <h2 class="text-center mb-4">Pay Structure</h2>
    <div id="successMessage" class="alert alert-success d-none" role="alert">
        Pay heads successfully added to employee!
    </div>
    <div class="row">
        <div class="col-md-4">
            <h4 class="text-center">Available Pay Heads</h4>
            <div class="list-box" id="availablePayHeads">
                <?php foreach ($payheads as $payhead): ?>
                    <div class="list-item <?= ($payhead['payhead_type'] == 'Deduction') ? 'deduction' : 'earning'; ?>"
                         data-id="<?= $payhead['payhead_id']; ?>"
                         data-name="<?= $payhead['payhead_name']; ?>"
                         data-type="<?= $payhead['payhead_type']; ?>">
                        <?= $payhead['payhead_name']; ?> (<?= $payhead['payhead_type']; ?>)
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-4">
            <h4 class="text-center">Selected Pay Heads</h4>
            <div class="list-box" id="selectedPayHeads"></div>
        </div>
        <div class="col-md-4">
            <h4 class="text-center">Enter Payhead Amount</h4>
            <form id="payheadForm">
                <div id="amountInputs"></div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Add Pay Heads to Employee</button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(document).on("click", ".list-item", function () {
                let payheadId = $(this).data("id");
                let payheadName = $(this).data("name");
                let payheadType = $(this).data("type");
                let amountField = `<div class='selected-list' data-id='${payheadId}'>
                                    <label>${payheadName} (${payheadType})</label>
                                    <input type='number' class='form-control amount-input' name='amount_${payheadId}' placeholder='Enter amount'>
                                  </div>`;
                
                if ($(this).parent().attr("id") === "availablePayHeads") {
                    $(this).appendTo("#selectedPayHeads");
                    $("#amountInputs").append(amountField);
                } else {
                    $(this).appendTo("#availablePayHeads");
                    $("#amountInputs .selected-list[data-id='" + payheadId + "']").remove();
                }
            });

            $("#payheadForm").submit(function (e) {
                e.preventDefault();
                let selectedPayHeads = [];
                $("#selectedPayHeads .list-item").each(function () {
                    let id = $(this).data("id");
                    let amount = $("input[name='amount_" + id + "']").val();
                    selectedPayHeads.push({ id, amount });
                });
                sessionStorage.setItem("payheads", JSON.stringify(selectedPayHeads));
                $("#successMessage").removeClass("d-none");
                $("#selectedPayHeads, #amountInputs").empty();
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
