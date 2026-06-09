<?php 
include_once('../../inc/function.php');
include_once('../../file/config.php'); // Include your database connection

// Check if the user is logged in
$logged_in_user = $_SESSION['username'] ?? null; // Replace with the appropriate session key
$user_role = $_SESSION['role'] ?? null; // Assuming you have a role stored in the session

if ($logged_in_user) {
    // Fetch data based on user role
    if (in_array($user_role, ['admin', 'document controller', 'quality controller', 'reviewer'])) {
        // Fetch all data for admin, document controller, and quality controller
        $sql = "SELECT 
            ci.checklist_id, 
            ci.checklist_no, 
            ci.project_no, 
            ci.inspected_by, 
            ci.equipment_type, 
            ci.checklist_type, 
            ci.client_name, 
            ci.equipment_no, 
            ci.crane_serial_no, 
            ci.sticker_no, 
            ci.location, 
            ci.created_at,
            pi.project_status 
                FROM checklist_information ci
                LEFT JOIN project_info pi ON ci.project_no = pi.project_no
                ORDER BY ci.created_at DESC";
        $stmt = $conn->prepare($sql);
    } else {
        // Fetch data only for the logged-in inspector
        $sql = "SELECT 
            ci.checklist_id, 
            ci.checklist_no, 
            ci.project_no, 
            ci.inspected_by, 
            ci.equipment_type, 
            ci.checklist_type, 
            ci.client_name,
            ci.equipment_no, 
            ci.crane_serial_no, 
            ci.sticker_no, 
            ci.location, 
            ci.created_at,
            pi.project_status  
                FROM checklist_information ci
                LEFT JOIN project_info pi ON ci.project_no = pi.project_no
                WHERE ci.inspected_by = ?
                ORDER BY ci.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $logged_in_user);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Redirect to login page if not logged in
    header("Location: ../../index.php");
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
                    <div class="card-body bg-white ">

                    <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2">Checklist List</h4>
                    </div>
                       <!-- <div class="col-6 text-right">-->
                       <!--     <a href="./add-checklist.php">-->
                       <!--<button type="button" class="btn">Create New</button>    -->
                       <!--</a>           -->
                       <!-- </div>-->
                    </div>
                    </div>

                    <div class="table-responsive">
                        <table class="order-list-table text-nowrap">
                            <thead>
                                <th></th>
                                    <th style="width: 70px;">Checklist No </th>
                                    <th>Project ID</th>
                                    <th>Inspect By</th>
                                    <th>Equipment</th>
                                    <th>Checklist Type</th>
                                    <th>Company</th>
                                    <th>Equipment No</th>
                                    <th>Serial No</th>
                                    <th>Sticker No </th>
                                    <th>Location</th>
                                    
                                    <!-- <th>Shipping</th>
                                    <th>Quantity</th> -->
                                    <!-- <th>Status</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
    <?php 
    // Check if records exist
    if (mysqli_num_rows($result) > 0) {
        // Loop through each row
        while ($row = mysqli_fetch_assoc($result)) {
            $checklist_type_raw = $row['checklist_type']; // Raw checklist type
            $checklist_no = $row['checklist_id']; // Get checklist number for each row
            
            // Format the checklist type: remove hyphens/underscores and capitalize words
            $formatted_checklist_type = str_replace(['-', '_'], ' ', $checklist_type_raw); // Replace hyphens/underscores with spaces
            $formatted_checklist_type = ucwords($formatted_checklist_type); // Capitalize each word
            
             // Get inspector's name from the database (assuming you already have it in $row['inspected_by'])
             $inspector_name = $row['inspected_by']; 

             // Normalize inspector name to lowercase
$inspector_folder = strtolower(str_replace(' ', '_', $inspector_name));
// $inspector_folder = strtolower($inspector_name);

// Define the path to the inspector's image
$inspector_image_path = "{$url}/inspector/uploads/{$inspector_folder}/images/profile_image.jpg";

// Convert to server file path to check if it exists
$server_image_path = $_SERVER['DOCUMENT_ROOT'] . "/inspector/uploads/{$inspector_folder}/images/profile_image.jpg";

// Check if the inspector's image exists on the server
if (file_exists($server_image_path)) {
    $inspector_image = $inspector_image_path; // Use the custom image
} else {
    // Fallback to a default image
    $inspector_image = "{$url}inspector/uploads/{$inspector_folder}/images/profile_image.jpg";
}

            
            echo "<tr>";
            echo "<td> 
                <div class='star'>
                    <a href='./type/view/{$checklist_type_raw}.php?checklist_type={$checklist_type_raw}&&checklist_no={$checklist_no}'>
                        <div class='icon text-primary'>
                            <i class='et-document'></i>
                        </div>
                    </a>
                </div>
            </td>";
            echo "<td>{$row['checklist_no']}</td>";
            echo "<td>{$row['project_no']}</td>";
            echo "<td style='white-space: nowrap; max-width: 200px;'>";
echo "<div style='display: flex; align-items: center; gap: 8px; overflow: hidden; text-overflow: ellipsis;'>";
echo "<img src='{$inspector_image}' alt='{$inspector_name}' class='inspector-image' style='width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;'>";
echo "<span style='overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; max-width: 140px;'>{$inspector_name}</span>";
echo "</div></td>";

            echo "<td>{$row['equipment_type']}</td>";
            
            // Use the formatted checklist type
            echo "<td>{$formatted_checklist_type}</td>";
            
            echo "<td>{$row['client_name']}</td>";
            echo "<td>{$row['equipment_no']}</td>";
            echo "<td>{$row['crane_serial_no']}</td>";
            echo "<td>{$row['sticker_no']}</td>";
            echo "<td>{$row['location']}</td>";
            // echo "<td>
            //     <button type='button' class='status-btn'>{$row['status']}</button>
            // </td>";
            echo "<td class='actions'>";
if ($row['project_status'] !== 'Completed') {
    if (in_array($user_role, ['inspector', 'admin', 'document controller', 'quality controller'])) {
        // Allow only inspectors to edit
        echo "<a href='./type/{$checklist_type_raw}.php?checklist_type={$checklist_type_raw}&&checklist_no={$checklist_no}'>
                <span class='contact-edit'>
                    <img src='{$url}assets/img/svg/c-edit.svg' alt='' class='svg'>
                </span>
              </a>";
    } else {
        // Disable edit button for non-inspectors
        echo "<span class='contact-edit disabled'>
                <img src='{$url}assets/img/svg/c-edit.svg' alt='' class='svg' style='opacity: 0.5; cursor: not-allowed;'>
              </span>";
    }

    // Render delete button for all roles
    echo "<a href='#' class='delete-checklist' data-checklist-no='{$checklist_no}'>
            <span class='contact-close'>
                <img src='{$url}assets/img/svg/c-close.svg' alt='' class='svg'>
            </span>
          </a>";
} else {
    // Disable buttons if project is completed
    echo "<span class='contact-edit disabled'>
            <img src='{$url}assets/img/svg/c-edit.svg' alt='' class='svg' style='opacity: 0.5; cursor: not-allowed;'>
          </span>";
    echo "<span class='contact-close disabled'>
            <img src='{$url}assets/img/svg/c-close.svg' alt='' class='svg' style='opacity: 0.5; cursor: not-allowed;'>
          </span>";
}
echo "</td>";

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
</div>
<!-- End Main Wrapper -->

<?php 
include_once('../../inc/footer.php');
?>

<!-- DataTables scripts -->
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
                title: 'Checklist Data',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Action column)
                }
            }
        ],
        "searching": true
    });



        // Delete functionality
        $(document).on('click', '.delete-checklist', function(e) {
            e.preventDefault();

            // Confirm before deletion
            if (confirm("Are you sure you want to delete this checklist?")) {
                const row = $(this).closest('tr'); // Get the row
                const checklistNo = $(this).data('checklist-no'); // Get the checklist number

                // Send AJAX request to delete the checklist
                $.ajax({
                    url: './delete_checklist.php', // Backend URL
                    type: 'POST',
                    data: { checklist_no: checklistNo }, // Data to send
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        if (response.status === 'success') {
                            row.fadeOut(300, function() { // Remove row from DOM
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