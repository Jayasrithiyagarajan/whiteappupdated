<?php 
session_start(); // Start the session to access logged-in user data


include_once('../inc/function.php');

include '../file/config.php'; // Database connection

// Fetch data from the project_info table
$sql = "SELECT * FROM project_info";
$result = $conn->query($sql);



// Get the logged-in inspector's ID or name from the session
$logged_in_inspector = $_SESSION['inspector_name'] ?? null;

if ($logged_in_inspector) {
    // Fetch data from the project_info table for the logged-in inspector
    $sql = "SELECT * FROM project_info WHERE inspector_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $logged_in_inspector);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Redirect to login page or display an error if inspector is not logged in
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
                    <!-- <div class="card-body bg-white ">

                    <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">Job List</h4>
                    </div>
                        <div class="col-6 text-right">
                       <button type="button" class="btn" >Create New</button>               
                        </div>
                    </div>
                    </div> -->
                    <div class="card mb-30">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between align-items-center">
            <h4 class="font-20">Job List</h4>

            <div class="d-flex flex-wrap">
                <!-- Date Picker -->
                <div class="mr-20 mt-3 mt-sm-0">
                   <!-- <span class="input-group-addon">
                      <img src="../../assets/img/svg/calender-color.svg" alt="" class="svg">
                    </span> -->

                  <a href="create-job.php"> <button type="button" class="btn" >Create New</button>   </a> 
                </div>
                <!-- End Date Picker -->

                <!-- Dropdown Button -->
                <div class="dropdown-button mt-3 mt-sm-0">
                    <button type="button" class="btn style--two orange" data-toggle="dropdown">Download <i class="icofont-simple-down"></i></button>

                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" id="print-button">Print</a>
                        <a class="dropdown-item" href="#" id="excel-button">EXL</a>
                        <a class="dropdown-item" href="#" id="pdf-button">PDF</a>
                    </div>
                </div>
                <!-- End Dropdown Button -->
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <!-- Invoice List Table -->
      
        <div class="table-responsive">
        <table id="job-table" class="order-list-table style--three table-centered text-nowrap">
    <thead>
        <tr>
            <th>Project ID</th>
            <th>Start Date</th>
            <th>Progress</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Equip. Type</th>
            <th>Location</th>
            <th>Inspector</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td class="bold"><?php echo "#" . str_pad($row["project_no"], 5, "0", STR_PAD_LEFT); ?></td>
                    <td><?php echo date("d M Y", strtotime($row["creation_date"])); ?></td>
                    <!-- <td>
                        <div class="product-img">
                        <badge class="primary">
                       <a href=""><i class="icofont-checked color-primary"></i> Checklist</a> </badge>
                       <a href=""><i class="icofont-edit color-primary"></i> Report</a> 
                       <a href=""><i class="icofont-data color-primary"></i> Certificate</a> 
                             <img src="../assets/img/product/product1.png" alt="">
                            <img src="../assets/img/product/product1.png" alt="">
                            <img src="../assets/img/product/product1.png" alt=""> 
                        </div>
                    </td> -->


                    <td>
    <div class="product-img">
        <?php if ($row['checklist_status'] === 'Pending') { ?>
            <a href="../document/checklist/add-checklist.php?project_no=<?php echo $row['project_no']; ?>" class="text-primary">
                <i class="icofont-checked color-primary"></i> Create Checklist
            </a>
        <?php } else { ?>
            <span class="text-success">
                <i class="icofont-check color-success"></i> Checklist Created
            </span>
        <?php } ?>

        <!-- Report Button Logic -->
        <!-- Report Button Logic -->
        <?php if ($row['checklist_status'] === 'Created') { ?>
    <?php if ($row['report_status'] === 'Pending') { ?>
        <a href="../document/report/create.php?project_no=<?php echo $row['project_no']; ?>" class="text-primary">
            <i class="icofont-edit color-primary"></i> Create Report
        </a>
    <?php } elseif ($row['report_status'] === 'Generated') { ?>
        <span class="text-success">
            <i class="icofont-check color-success"></i> Report Created
        </span>
    <?php } else { ?>
        <span class="text-muted">
            <i class="icofont-lock"></i> Report Locked
        </span>
    <?php } ?>
<?php } else { ?>
    <span class="text-muted">
        <i class="icofont-lock"></i> Checklist Pending
    </span>
<?php } ?>

        <!-- Certificate Link -->
        <!-- <a href="generate-certificate.php?id=<?php echo $row['project_no']; ?>">
            <i class="icofont-data color-primary"></i> Certificate
        </a>  -->
    </div>
</td>

                    <td><?php echo htmlspecialchars($row["customer_name"]); ?></td>
                    <td class="text-danger">Processing</td>
                    <td><?php echo htmlspecialchars($row["equipment_type"]); ?></td>
                    <td><?php echo htmlspecialchars($row["equipment_location"]); ?></td>
                    <td><?php echo htmlspecialchars($row["inspector_name"]); ?></td>
                    <td>
                        <a href="job-details.php?id=<?php echo $row['project_no']; ?>">
                            <button type="button" class="details-btn">
                                Details <i class="icofont-arrow-right"></i>
                            </button>
                        </a>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='9' class='text-center'>No records found.</td></tr>";
        }
        ?>
    </tbody>
</table>
               </div>
        <!-- End Invoice List Table -->
    </div>
</div>
<?php
// Close the database connection
$conn->close();
?>

<!-- Include the export scripts -->
<script>
// document.getElementById('pdf-button').addEventListener('click', function () {
//     const { jsPDF } = window.jspdf;
//     const doc = new jsPDF();
//     doc.autoTable({ html: '#job-table' });
//     doc.save('job-list.pdf');
// });

document.getElementById('excel-button').addEventListener('click', function () {
    var wb = XLSX.utils.table_to_book(document.getElementById('job-table'), { sheet: "Sheet JS" });
    XLSX.writeFile(wb, 'job-list.xlsx');
});
</script>


                  
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
include_once('../inc/footer.php');
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
                    title: 'job List'
                }
            ],
            "searching": true
        });
    });
</script>

