<?php
session_start();
include '../file/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Job List</title>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
body{background:#f4f6f9;font-family:Inter,system-ui}
.kpi-card{border-radius:14px;padding:20px;color:#fff;box-shadow:0 6px 18px rgba(0,0,0,.08)}
.kpi-total{background:linear-gradient(135deg,#6f42c1,#4e73df)}
.kpi-pending{background:linear-gradient(135deg,#f6c23e,#e74a3b)}
.kpi-completed{background:linear-gradient(135deg,#1cc88a,#20c997)}

.dataTables_wrapper{
    background:#fff;padding:15px;border-radius:14px;
    box-shadow:0 4px 12px rgba(0,0,0,.06)
}
table.dataTable thead th{
    background:#f8f9fc;font-weight:600;
    position:sticky;top:0;z-index:10
}
.badge-status{padding:5px 12px;font-size:12px;border-radius:20px}
.badge-pending{background:#fdecea;color:#e74a3b}
.badge-completed{background:#e6f4ea;color:#1cc88a}
</style>
</head>

<body>

<div class="container-fluid mt-4">

<!-- KPI -->
<div class="row mb-4">
    <div class="col-md-4"><div class="kpi-card kpi-total"><h6>Total</h6><h3 id="kpi-total">0</h3></div></div>
    <div class="col-md-4"><div class="kpi-card kpi-pending"><h6>Pending</h6><h3 id="kpi-pending">0</h3></div></div>
    <div class="col-md-4"><div class="kpi-card kpi-completed"><h6>Completed</h6><h3 id="kpi-completed">0</h3></div></div>
</div>

<table id="job-table" class="display nowrap" style="width:100%">
<thead>
<tr>
    <th>Project No</th>
    <th>Inspection Date</th>
    <th>Checklist</th>
    <th>Report</th>
    <th>Reviewer</th>
    <th>Certificate Status</th>
    <th>Certificate Type</th>
    <th>Customer</th>
    <th>Project Status</th>
    <th>Equipment ID</th>
    <th>Equipment Type</th>
    <th>Checklist Type</th>
    <th>Sticker No</th>
    <th>Inspector</th>
    <th>Action</th>
</tr>
</thead>
</table>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$('#job-table').DataTable({
    processing:true,
    serverSide:true,
    scrollX:true,
    deferRender:true,
    order:[[0,'desc']],
    ajax:{
        url:'fetch_jobs.php',
        type:'POST',
        dataSrc:function(json){
            $('#kpi-total').text(json.kpi.total);
            $('#kpi-pending').text(json.kpi.pending);
            $('#kpi-completed').text(json.kpi.completed);
            return json.data;
        }
    },
    columns:[
        {data:'project_no'},
        {data:'inspection_date'},
        {data:'checklist_status'},
        {data:'report_status'},
        {data:'review_status'},
        {data:'certificatestatus'},
        {data:'certificate_types'},
        {data:'customer_name'},
        {
            data:'project_status',
            render:d=>d==='Completed'
                ? '<span class="badge-status badge-completed">Completed</span>'
                : '<span class="badge-status badge-pending">Pending</span>'
        },
        {data:'equipment_id'},
        {data:'equipment_type'},
        {data:'checklist_type'},
        {data:'sticker_no'},
        {data:'inspector_name'},
        {
            data:'project_no',
            orderable:false,
            render:id=>`<a href="job-details.php?id=${id}" class="btn btn-sm btn-primary">View</a>`
        }
    ],
    dom:'Bfrtip',
    buttons:[{extend:'excelHtml5',title:'Job_List'}]
});
</script>

</body>
</html>
