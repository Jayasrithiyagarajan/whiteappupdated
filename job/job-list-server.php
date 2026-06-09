<?php
include_once('../inc/function.php');
include '../file/config.php';
session_start();

$user_role = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

$cols = [
  "project_no","creation_date","checklist_status","report_status","review_status",
  "certificatestatus","customer_name","project_status","checklist_type",
  "equipment_type","equipment_id","equipment_location","inspector_name"
];

$limit  = intval($_GET['length']);
$offset = intval($_GET['start']);
$search = $_GET['search']['value'];
$order_i = intval($_GET['order'][0]['column']);
$order_col = $cols[$order_i] ?? 'creation_date';
$order_dir = $_GET['order'][0]['dir'] === 'asc' ? 'ASC' : 'DESC';

// Build filters
$where = "";
$params = [];
$types = "";

if ($user_role === 'customer') {
  $where = "WHERE customer_name = ?";
  $params[] = $username; $types .= "s";
} elseif (!in_array($user_role, ['admin','reviewer','quality controller','document controller'])) {
  $where = "WHERE inspector_name = ?";
  $params[] = $username; $types .= "s";
}

if ($search !== '') {
  $like = "%{$search}%";
  $sf = "(project_no LIKE ? OR customer_name LIKE ? OR equipment_id LIKE ? OR inspector_name LIKE ?)";
  $where .= $where ? " AND $sf" : "WHERE $sf";
  $params = array_merge($params, [$like, $like, $like, $like]);
  $types .= "ssss";
}

// Count
$countSQL = "SELECT COUNT(*) FROM project_info $where";
$stmt = $conn->prepare($countSQL);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();

// Fetch
$params[] = $offset; $params[] = $limit; $types .= "ii";
$sql = "SELECT " . implode(",", $cols) . " FROM project_info $where ORDER BY $order_col $order_dir LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($r = $res->fetch_assoc()) {
  $id = "#".str_pad($r['project_no'],5,"0",STR_PAD_LEFT);
  $date = date("d M Y", strtotime($r['creation_date']));
  // Build buttons...
  $progress = '<div class="product-img">';
  if ($user_role==='inspector') {
    $progress .= ($r['checklist_status']==='Pending') ?
      '<a href="../document/checklist/add-checklist.php?project_no='.$r['project_no'].'" class="text-primary"><i class="icofont-checked"></i> Create Checklist</a>' :
      '<span class="text-success"><i class="icofont-check"></i> Checklist Created</span>';
    if ($r['checklist_status']==='Created') {
      $progress .= ($r['report_status']==='Pending') ?
        '<br><a href="../document/report/create.php?project_no='.$r['project_no'].'" class="text-primary"><i class="icofont-edit"></i> Create Report</a>' :
        ($r['report_status']==='Generated' ?
          '<br><span class="text-success"><i class="icofont-check"></i> Report Created</span>' :
          '<br><span class="text-muted"><i class="icofont-lock"></i> Report Locked</span>');
    } else {
      $progress .= '<br><span class="text-muted"><i class="icofont-lock"></i> Checklist Pending</span>';
    }
  } else {
    $progress .= '<span class="text-muted"><i class="icofont-lock"></i> Access Restricted</span>';
  }
  $progress .= '</div>';

  $status = '<a href="#" class="btn s_alert '.($r['project_status']==='Completed'?'bg-success-light text-success':'bg-danger-light text-danger').'" style="padding:6px 9px;font-size:11px;">'.($r['project_status']==='Completed'?'Completed':'Pending').'</a>';
  $details = '<a href="job-details.php?id='.$r['project_no'].'"><button class="btn btn-sm" style="padding:6px 9px;font-size:11px;">Details <i class="icofont-arrow-right"></i></button></a>';
  $delete = ($user_role==='admin')?'<button type="button" class="text-danger" onclick="deleteProject(\''.$r['project_no'].'\')" style="padding:6px 9px;font-size:14px;"><i class="icofont-trash"></i></button>':'';

  $data[] = [
    "project_no"=>$id,
    "creation_date"=>$date,
    "progress_html"=>$progress,
    "checklist_status"=>$r['checklist_status'],
    "report_status"=>$r['report_status'],
    "review_status"=>$r['review_status'],
    "certificatestatus"=>ucfirst($r['certificatestatus']),
    "customer_name"=>$r['customer_name'],
    "project_status_html"=>$status,
    "action_html"=>$details,
    "equipment_id"=>$r['equipment_id'],
    "checklist_type"=>ucwords(str_replace(['-','_'],' ',$r['checklist_type'])),
    "equipment_type"=>$r['equipment_type'],
    "equipment_location"=>ucfirst($r['equipment_location']),
    "inspector_name"=>$r['inspector_name'],
    "delete_action"=>$delete
  ];
}

echo json_encode([
  "draw"=>intval($_GET['draw']),
  "recordsTotal"=>$total,
  "recordsFiltered"=>$total,
  "data"=>$data
]);
