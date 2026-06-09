<?php 
include_once('../../inc/function.php');
include_once('../../file/config.php'); // Include your database connection

// Check if the user is logged in
$logged_in_user = $_SESSION['username'] ?? null; // Replace with the appropriate session key
$user_role = $_SESSION['role'] ?? null; // Assuming you have a role stored in the session

if ($logged_in_user) {
    // Fetch data based on user role
    if ($user_role === 'customer') {
        // Fetch only data for the logged-in customer
        $sql = "SELECT ci.*, pi.project_status 
                FROM checklist_information ci
                LEFT JOIN project_info pi ON ci.project_no = pi.project_no
                WHERE ci.client_name = ? OR pi.customer_name = ?
                ORDER BY ci.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $logged_in_user, $logged_in_user);
    } else {
        // For other roles, redirect or handle differently
        header("Location: ../../index.php");
        exit;
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
                            <div class="col-12">
                                <h4 class="pl-2 pt-3 pb-2">My Checklists</h4>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="order-list-table text-nowrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="width: 70px;">Checklist No</th>
                                    <th>Project ID</th>
                                    <th>Inspect By</th>
                                    <th>Equipment</th>
                                    <th>Checklist Type</th>
                                    <th>Status</th>
                                    <!-- <th>Action</th> -->
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
            
            // Format the checklist type
            $formatted_checklist_type = str_replace(['-', '_'], ' ', $checklist_type_raw);
            $formatted_checklist_type = ucwords($formatted_checklist_type);
            
            // Get inspector's name
            $inspector_name = $row['inspected_by']; 
            $inspector_image_path = "../../inspector/uploads/{$inspector_name}/images/profile_image.jpg";
            $inspector_image = file_exists($inspector_image_path) ? $inspector_image_path : "../uploads/default_profile_image.jpg";
            
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
            echo "<td>
                <img src='{$inspector_image}' alt='{$inspector_name}' class='inspector-image' style='width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;'>
                {$inspector_name}
            </td>";
            echo "<td>{$row['equipment_type']}</td>";
            echo "<td>{$formatted_checklist_type}</td>";
            echo "<td>
                <button type='button' class='status-btn'>{$row['project_status']}</button>
            </td>";
            echo "<td class='actions'>";
            // Customers can only view, not edit or delete
            // echo "<a href='./type/view/{$checklist_type_raw}.php?checklist_type={$checklist_type_raw}&&checklist_no={$checklist_no}'>
            //         <span class='contact-view'>
            //             <img src='{$url}assets/img/svg/c-view.svg' alt='View' class='svg'>
            //         </span>
            //       </a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'>No checklists found</td></tr>";
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

<!-- DataTables scripts -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function() {
        $('.order-list-table').DataTable({
            "searching": true,
            "ordering": true,
            "info": true,
            "paging": true
        });
    });
</script>