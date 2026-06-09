<?php 
// session_start();
include_once('../../inc/function.php');
include_once('../../file/config.php'); // Include database connection

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Build base SQL with only required columns
$sql = "SELECT 
            lgc.project_no,
            lgc.certificate_no,
            lgc.type,
            lgc.inspector,
            lgc.customer_name,
            lgc.address_of_premises,
            lgc.date_of_this_examination,
            pi.project_status
        FROM lifting_gear_certificates lgc
        LEFT JOIN project_info pi ON lgc.project_no = pi.project_no";

// Apply filter for inspector
if ($role === 'inspector') {
    $sql .= " WHERE lgc.inspector = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>


<!-- Main Content -->
<div class="main-content d-flex flex-column flex-md-row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card bg-transparent">
                    <!-- Contact Header -->
                    <div class="contact-header d-flex align-items-sm-center media flex-column flex-sm-row bg-white mb-30">
                        <div class="contact-header-left media-body d-flex align-items-center mr-4">
                            <div class="card-body bg-white">
                                <div class="main-header-btn">
                                    <a href="#" class="btn">Lifting Gears Certificate</a>
                                </div>
                            </div>
                            <!-- Search Form -->
                            <form action="#" class="search-form flex-grow">
                                <div class="theme-input-group style--two">
                                    <input type="text" class="theme-input-style" placeholder="Search Here" id="search-input">
                                    <button type="submit">
                                        <img src="<?php echo $url; ?>assets/img/svg/search-icon.svg" alt="" class="svg">
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="contact-header-right d-flex align-items-center justify-content-end mt-3 mt-sm-0">
                            <!-- Add New Contact Btn -->
                            <div class="add-new-contact mr-20">
                                <a href="./create.php" class="btn-circle">
                                    <img src="<?php echo $url; ?>assets/img/svg/plus_white.svg" alt="" class="svg">
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Contact Header -->

                    <div class="table-responsive">
                        <!-- Certificate List Table -->
                        <table id="inspection-table" class="contact-list-table text-nowrap bg-white">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Project ID</th>
                                    <th>Certificate No</th>
                                    <th>Inspected Items</th>
                                    <th>Serial No. / Equipment Id</th>
                                    <th class="text-center">Inspector Name</th>
                                    <th>Client Name</th>
                                    <th>Location</th>
                                    <th>Date of This Examination</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr id="row_<?php echo $row['project_no']; ?>">
                                        <td>
        <input type="checkbox" class="mr-2">
        <div class='star d-flex align-items-center'>
            <a href='./view.php?project_no=<?php echo $row['project_no']; ?>' target="_blank" class='mr-2' title="View">
                <div class='icon text-primary'>
                    <i class='et-clipboard'></i>
                </div>
            </a>
            <a href='./download.php?project_no=<?php echo $row['project_no']; ?>' title="Download">
                <div class='icon text-primary'>
                    <i class='et-download'></i>
                </div>
            </a>
        </div>
    </td>
                                            
                                            <td><?php echo htmlspecialchars($row['project_no']); ?></td>
                                            <td><?php echo htmlspecialchars($row['certificate_no']); ?></td>
                                            <td><?php echo htmlspecialchars($row['type']); ?></td>
                                            <td> NA </td>
                                            <td>
    <div class="d-flex align-items-center">
        <div class="img mr-20">
    <?php
    $inspectorName = $row['inspector'];
    // Convert name to lowercase and replace spaces with underscores
    $folderName = strtolower(str_replace(' ', '_', $inspectorName));
    
    $imagePath = "../../inspector/uploads/" . htmlspecialchars($folderName) . "/images/profile_image.jpg";
    $defaultImage = "../../assets/img/avatar/default-avatar.png";
    ?>
    <img src="<?php echo file_exists($imagePath) ? $imagePath : $defaultImage; ?>" 
         class="img-40" 
         alt="<?php echo htmlspecialchars($inspectorName); ?>">
</div>

        <div class="name bold">
            <?php echo htmlspecialchars($inspectorName); ?>
        </div>
    </div>
</td>
<td><?php echo htmlspecialchars($row['customer_name']); ?></td>
<td><?php echo htmlspecialchars($row['address_of_premises']); ?></td>
<td><?php echo htmlspecialchars($row['date_of_this_examination']); ?></td>
                                            <td class="actions">
                                                <!-- Check if the user is 'document controller' and project is not completed -->
                                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'document controller' && $row['project_status'] !== 'Completed'): ?>
                                                    <span class='contact-edit' onclick='redirectToEditLifting(<?php echo json_encode($row['project_no']); ?>)'>
                                                        <img src='<?php echo $url; ?>assets/img/svg/c-edit.svg' alt='' class='svg'>
                                                    </span>
                                                <?php else: ?>
                                                    <span class='contact-edit disabled'>
                                                        <img src='<?php echo $url; ?>assets/img/svg/c-edit.svg' alt='' class='svg' style='opacity: 0.5; cursor: not-allowed;'>
                                                    </span>
                                                <?php endif; ?>

                                                <!-- Delete action -->
                                                <!-- <span class='contact-close' onclick='deleteRow(<?php echo json_encode($row['project_no']); ?>)'>
                                                    <img src='<?php echo $url; ?>assets/img/svg/c-close.svg' alt='' class='svg'>
                                                </span> -->

                                                <!-- View & Download icons -->
    
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan='8'>No records found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- End Certificate List Table -->
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
</div>
<!-- End Main Content -->

// <script>
// document.getElementById('search-input').addEventListener('keyup', function () {
//     const filter = this.value.toLowerCase();
//     const rows = document.querySelectorAll('#inspection-table tbody tr');

//     rows.forEach(row => {
//         const rowText = row.textContent.toLowerCase();
//         row.style.display = rowText.includes(filter) ? '' : 'none';
//     });
// });
// </script>


<script>
// Function to delete a row via AJAX
function deleteRow(projectNo) {
    if (confirm('Are you sure you want to delete this row?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_lifting.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                var row = document.getElementById('row_' + projectNo);
                if (row) {
                    row.remove();
                } else {
                    alert('Row not found.');
                }
            } else {
                alert('Error: Unable to delete record.');
            }
        };

        xhr.send('project_no=' + encodeURIComponent(projectNo));
    }
}

// Function to redirect to edit page
function redirectToEditLifting(project_no) {
    console.log("Redirecting to edit_lifting.php with project_no:", project_no);
    if (project_no) {
        window.location.href = 'edit_lifting.php?project_no=' + encodeURIComponent(project_no);
    } else {
        console.error("Project number is undefined.");
    }
}
</script>

<?php 
// Close database connection
$conn->close();
include_once('../../inc/footer.php');
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>


<script>
$(document).ready(function () {
    $('#inspection-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export',
                title: 'Lifting Gear Certificates',
                exportOptions: {
                    columns: ':not(:last-child)' // skip the Actions column
                }
            }
        ],
        order: [[8, 'desc']], // Order by Date of Examination
        responsive: true,
        pageLength: 10
    });
});
</script>
