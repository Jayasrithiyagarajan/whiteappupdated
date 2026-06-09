<?php 
session_start(); // Start session to access logged-in inspector details
include_once('../inc/function.php');
include_once('../file/config.php'); // Include your database connection

// Check if the inspector is logged in
$logged_in_inspector = $_SESSION['inspector_name'] ?? null; // Replace with the appropriate session key

if ($logged_in_inspector) {
    // Fetch data from checklist_information table for the logged-in inspector
    $sql = "SELECT * FROM checklist_information WHERE inspected_by = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $logged_in_inspector);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit;
}
?>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card bg-transparent">
                    <div class="card-body bg-white">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="pl-2 pt-3 pb-2">Checklist List</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a href="./add-checklist.php">
                                    <button type="button" class="btn">Create New</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="order-list-table text-nowrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Checklist No</th>
                                    <th>Project ID</th>
                                    <th>Inspected By</th>
                                    <th>Equipment</th>
                                    <th>Company</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <?php 
                                // Check if records exist
                                if ($result->num_rows > 0) {
                                    // Loop through each row
                                    while($row = $result->fetch_assoc()) {
                                        $checklist_type = $row['checklist_type']; // Get checklist type for each row
                                        $checklist_no = $row['checklist_id']; // Get checklist number for each
                                        echo "<tr>";
                                        echo "<td> 
                                                <div class='star'>
                                                    <a href='./type/view/{$checklist_type}.php?checklist_type={$checklist_type}&&checklist_no={$checklist_no}'>
                                                        <div class='icon text-primary'>
                                                            <i class='et-document'></i>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>";
                                        echo "<td>{$row['checklist_no']}</td>";
                                        echo "<td>{$row['project_no']}</td>";
                                        echo "<td>{$row['inspected_by']}</td>";
                                        echo "<td>{$row['equipment_type']}</td>";
                                        echo "<td>{$row['client_name']}</td>";
                                        echo "<td>
                                            <button type='button' class='status-btn'>{$row['status']}</button>
                                        </td>";
                                        echo "<td class='actions'>
                                                <a href='./type/{$checklist_type}.php?checklist_type={$checklist_type}&&checklist_no={$checklist_no}'>
                                                    <span class='contact-edit'>
                                                        <img src='{$url}assets/img/svg/c-edit.svg' alt='' class='svg'>
                                                    </span>
                                                </a>
                                                <a href='#' class='delete-checklist' data-checklist-no='{$checklist_no}'>
                                                    <span class='contact-close'>
                                                        <img src='{$url}assets/img/svg/c-close.svg' alt='' class='svg'>
                                                    </span>
                                                </a>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
</div>
<!-- End Main Content -->

<?php 
include_once('../../inc/footer.php');
?>

<!-- DataTables Scripts -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>
    $(document).ready(function() {
        $('.order-list-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export', // Change button text
                    title: 'Checklist List'
                }
            ],
            "searching": true
        });

        // Delete functionality
        $(document).on('click', '.delete-checklist', function(e) {
            e.preventDefault();

            if (confirm("Are you sure you want to delete this checklist?")) {
                const row = $(this).closest('tr');
                const checklistNo = $(this).data('checklist-no');

                $.ajax({
                    url: './delete_checklist.php',
                    type: 'POST',
                    data: { checklist_no: checklistNo },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            row.fadeOut(300, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('Failed to delete the checklist: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while processing your request.');
                    }
                });
            }
        });
    });
</script>
