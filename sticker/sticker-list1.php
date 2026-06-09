<?php
// session_start();
include_once('../inc/function.php');
include '../file/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sticker List</title>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
body{background:#f4f6f9}
.dataTables_wrapper{
    background:#fff;
    padding:15px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.06);
}
table.dataTable thead th{
    background:#f8f9fc;
    font-weight:600;
    position:sticky;
    top:0;
}
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}
.badge-active{background:#e6f4ea;color:#1cc88a}
.badge-expired{background:#fdecea;color:#e74a3b}
.badge-hold{background:#fff3cd;color:#856404}
.kpi-card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.06);
}
.kpi-card h6{
    font-size:14px;
    color:#6c757d;
    margin-bottom:6px;
}
.kpi-card h3{
    margin:0;
    font-weight:700;
}
.kpi-card.success h3{color:#1cc88a}
.kpi-card.danger h3{color:#e74a3b}

</style>
</head>

<body>
<div class="main-content">
<div class="container-fluid mt-4">

<div class="row mb-4">
    <div class="col-md-4">
        <div class="kpi-card">
            <h6>Total Stickers</h6>
            <h3 id="kpi-total">0</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card success">
            <h6>Sticker Passed</h6>
            <h3 id="kpi-passed">0</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card danger">
            <h6>Sticker Failed</h6>
            <h3 id="kpi-failed">0</h3>
        </div>
    </div>
</div>



<h4 class="mb-4">STICKER LIST</h4>

<table id="sticker-table" class="display nowrap" style="width:100%">
<thead>
<tr>
    <th>Sticker ID</th>
    <th>Project No</th>
    <th>Inspector</th>
    <th>Created</th>
    <th>Inspection Date</th>
    <th>Expiry Date</th>
    <th>Sticker Result</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>
</table>
</div>
</div>
<?php include_once('../inc/footer.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$('#sticker-table').DataTable({
    processing:true,
    serverSide:true,
    deferRender:true,
    scrollX:true,
    order:[[3,'desc']],
    ajax:{
        url:'fetch_stickers.php',
        type:'POST',
        dataSrc:function(json){

            // 🔥 KPI values
            $('#kpi-total').text(json.kpi.total);
            $('#kpi-passed').text(json.kpi.passed);
            $('#kpi-failed').text(json.kpi.failed);

            return json.data;
        }
    },
    columns:[
        {data:'sticker_no'},
        {data:'project_no'},
        {data:'assign_inspector'},
        {data:'created_at'},
        {data:'inspection_date'},
        {data:'expiry_date'},
        {
            data:'sticker_status',
            render:d=>{
                if(d==='Passed') return '<span class="badge badge-active">Passed</span>';
                if(d==='Failed') return '<span class="badge badge-expired">Failed</span>';
                return '<span class="badge badge-hold">Pending</span>';
            }
        },
        {
            data:'status',
            render:d=>{
                if(d==='expired') return '<span class="badge badge-expired">Expired</span>';
                if(d==='active') return '<span class="badge badge-active">Active</span>';
                return '<span class="badge badge-hold">On Hold</span>';
            }
        },
        {
            data:null,
            orderable:false,
            render:row=>{
                if(row.sticker_status==='Passed'){
                    return `<a href="download-white.php?sticker_start_no=${row.sticker_no}" target="_blank">Download</a>`;
                }
                if(row.sticker_status==='Failed'){
                    return `<a href="download.php?sticker_start_no=${row.sticker_no}" target="_blank">Download</a>`;
                }
                return '';
            }
        }
    ],
    dom:'Bfrtip',
    buttons:[
        {extend:'excelHtml5',title:'Sticker_List'}
    ]
});

</script>

</body>


</html>
