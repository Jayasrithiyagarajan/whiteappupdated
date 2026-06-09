<?php
session_start();
include '../file/config.php';

$user = $_SESSION['username'];
$role = $_SESSION['role'];

$draw   = intval($_POST['draw']);
$start  = intval($_POST['start']);
$length = intval($_POST['length']);
$search = $_POST['search']['value'] ?? '';
$status = $_POST['status'] ?? '';

$columns = [
    'pi.project_no','pi.creation_date','pi.checklist_status','pi.report_status',
    'pi.review_status','pi.certificatestatus','pi.customer_name','pi.project_status',
    'pi.equipment_id','pi.checklist_type','ci.sticker_no','certificate_type',
    'pi.equipment_type','pi.equipment_location','pi.inspector_name'
];

$orderCol = $columns[$_POST['order'][0]['column']] ?? 'pi.project_no';
$orderDir = $_POST['order'][0]['dir'] ?? 'desc';

$where = " WHERE 1=1 ";
$params = [];
$types = "";

/* ROLE FILTER */
if ($role === 'customer') {
    $where .= " AND pi.customer_name = ?";
    $params[] = $user; $types .= "s";
} elseif ($role === 'inspector') {
    $where .= " AND pi.inspector_name = ?";
    $params[] = $user; $types .= "s";
}

/* STATUS */
if ($status !== '') {
    $where .= " AND pi.project_status = ?";
    $params[] = $status; $types .= "s";
}

/* SEARCH */
if ($search !== '') {
    $where .= " AND (
        pi.project_no LIKE ? OR
        pi.customer_name LIKE ? OR
        pi.inspector_name LIKE ? OR
        pi.equipment_id LIKE ?
    )";
    for ($i=0;$i<4;$i++) {
        $params[] = "%$search%";
        $types .= "s";
    }
}

/* TOTAL */
$totalRecords = $conn->query("SELECT COUNT(*) t FROM project_info")
                     ->fetch_assoc()['t'];

/* FILTERED */
$countSql = "
SELECT COUNT(DISTINCT pi.project_no) t
FROM project_info pi
LEFT JOIN checklist_information ci ON pi.project_no=ci.project_no
$where";

$stmt = $conn->prepare($countSql);
if ($params) $stmt->bind_param($types,...$params);
$stmt->execute();
$totalFiltered = $stmt->get_result()->fetch_assoc()['t'];

/* DATA */
$dataSql = "
SELECT
    pi.project_no, pi.creation_date, pi.checklist_status,
    pi.report_status, pi.review_status, pi.certificatestatus,
    pi.customer_name, pi.project_status, pi.equipment_id,
    pi.checklist_type, ci.sticker_no, pi.equipment_type,
    pi.equipment_location, pi.inspector_name,
    GROUP_CONCAT(DISTINCT ct.certificate_type) certificate_type
FROM project_info pi
LEFT JOIN checklist_information ci ON pi.project_no=ci.project_no
LEFT JOIN (
    SELECT project_no,'Healthcheck' certificate_type FROM crane_health_check_certificate
    UNION ALL SELECT project_no,'LoadTest' FROM loadtest_certificate
    UNION ALL SELECT project_no,'Mobile' FROM mobile_crane_loadtest
    UNION ALL SELECT project_no,'WithLoad' FROM withload
    UNION ALL SELECT project_no,'Lifting' FROM lifting_gear_certificates
    UNION ALL SELECT project_no,'MPI' FROM mpi_certificates
    UNION ALL SELECT project_no,'EddyCurrent' FROM eddy_current_inspection
    UNION ALL SELECT project_no,'LPI' FROM liquid_penetrant_inspection
    UNION ALL SELECT project_no,'RockTest' FROM rocking_test_certificate
    UNION ALL SELECT project_no,'LMI' FROM lmi_certificates
) ct ON pi.project_no = ct.project_no
$where
GROUP BY pi.project_no
ORDER BY $orderCol $orderDir
LIMIT ?, ?";

$params[] = $start; $params[] = $length; $types .= "ii";

$stmt = $conn->prepare($dataSql);
$stmt->bind_param($types,...$params);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($r = $res->fetch_assoc()) {

    $badges = '<span class="badge badge-secondary">N/A</span>';
    if ($r['certificate_type']) {
        $badges='';
        foreach (explode(',',$r['certificate_type']) as $c)
            $badges .= "<span class='badge badge-success mr-1'>$c</span>";
    }

    $data[] = [
        "project_no" => "#".str_pad($r['project_no'],5,"0",STR_PAD_LEFT),
        "creation_date" => date("d M Y",strtotime($r['creation_date'])),
        "checklist_status" => $r['checklist_status'],
        "report_status" => $r['report_status'],
        "review_status" => $r['review_status'],
        "certificatestatus" => ucfirst($r['certificatestatus']),
        "customer_name" => $r['customer_name'],
        "project_status" => $r['project_status'],
        "equipment_id" => $r['equipment_id'],
        "checklist_type" => ucwords(str_replace(['-','_'],' ',$r['checklist_type'])),
        "sticker_no" => $r['sticker_no'] ?? 'N/A',
        "certificate_type" => $badges,
        "equipment_type" => $r['equipment_type'],
        "equipment_location" => ucfirst($r['equipment_location']),
        "inspector_name" => $r['inspector_name'],
        "action" => ($role==='admin')
            ? "<span class='action-btn'><i class='icofont-trash text-danger'
               onclick='deleteProject({$r['project_no']})'></i></span>"
            : ''
    ];
}

echo json_encode([
    "draw"=>$draw,
    "recordsTotal"=>$totalRecords,
    "recordsFiltered"=>$totalFiltered,
    "data"=>$data
]);
