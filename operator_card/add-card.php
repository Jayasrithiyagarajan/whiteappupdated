<?php
include_once('../inc/function.php');
include_once('../file/config.php');

// Fetch inspectors for the Examiner dropdown
$inspectors = [];
$sql = "SELECT inspector_name FROM inspectors ORDER BY inspector_name ASC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $inspectors[] = $row['inspector_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Operator Card</title>
    <style>
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .form-header {
            margin-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }
        .form-header h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
        }
        .row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .col {
            flex: 1;
            min-width: 200px;
        }
        .btn-submit {
            background-color: #4f46e5;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background-color: #4338ca;
        }
        .equipment-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }
        .btn-remove {
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            line-height: 24px;
            text-align: center;
        }
        .btn-add {
            background: #22c55e;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
            cursor: pointer;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container-fluid">
            <div class="form-container">
                <div class="form-header">
                    <h2>Create New Operator Card</h2>
                </div>
                <form action="process-add-card.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="operator_name">Operator Name</label>
                                <input type="text" name="operator_name" id="operator_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="operator_photo">Operator Photo</label>
                                <input type="file" name="operator_photo" id="operator_photo" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="certificate_no">Certificate No</label>
                                <input type="text" name="certificate_no" id="certificate_no" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="id_iqama">ID / Iqama</label>
                                <input type="text" name="id_iqama" id="id_iqama" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="company">Company</label>
                                <input type="text" name="company" id="company" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="operating_location">Operating Location</label>
                                <input type="text" name="operating_location" id="operating_location" class="form-control" placeholder="e.g. ONSHORE OR OFFSHORE" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="issue_date">Issue Date</label>
                                <input type="date" name="issue_date" id="issue_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="examiner_name">Examiner (Inspector)</label>
                                <select name="examiner_name" id="examiner_name" class="form-control" required>
                                    <option value="">Select Inspector</option>
                                    <?php foreach ($inspectors as $inspector): ?>
                                        <option value="<?= htmlspecialchars($inspector); ?>"><?= htmlspecialchars($inspector); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="operator_designation">Operator Designation</label>
                                <input type="text" name="operator_designation" id="operator_designation" class="form-control" placeholder="e.g. BACKHOE LOADER / ROLLER COMPACTOR OPERATOR" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Equipment Details (Back of Card)</label>
                        <div id="equipment-container">
                            <div class="equipment-row">
                                <input type="text" name="equipment_details[]" class="form-control" placeholder="Enter equipment details" required>
                                <button type="button" class="btn-remove" onclick="removeRow(this)">×</button>
                            </div>
                        </div>
                        <button type="button" class="btn-add" onclick="addRow()">+ Add More Equipment</button>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="submit" class="btn-submit">Create Operator Card</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addRow() {
            const container = document.getElementById('equipment-container');
            const row = document.createElement('div');
            row.className = 'equipment-row';
            row.innerHTML = `
                <input type="text" name="equipment_details[]" class="form-control" placeholder="Enter equipment details" required>
                <button type="button" class="btn-remove" onclick="removeRow(this)">×</button>
            `;
            container.appendChild(row);
        }

        function removeRow(btn) {
            const rows = document.querySelectorAll('.equipment-row');
            if (rows.length > 1) {
                btn.parentElement.remove();
            } else {
                alert("At least one equipment detail is required.");
            }
        }
    </script>

    <?php include_once('../inc/footer.php'); ?>
</body>
</html>
