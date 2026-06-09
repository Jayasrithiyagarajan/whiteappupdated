<?php
include('../inc/function.php');
include "../file/config.php";

/* ---------------- FILTER INPUTS ---------------- */
$year            = $_GET['year'] ?? '';
$customer_name   = $_GET['customer_name'] ?? '';
$inspector_name  = $_GET['inspector_name'] ?? '';
$project_status  = $_GET['project_status'] ?? '';

$conditions = [];
$params = [];
$types = "";

if ($year != '') {
    $conditions[] = "YEAR(creation_date) = ?";
    $params[] = $year;
    $types .= "i";
}
if ($customer_name != '') {
    $conditions[] = "customer_name = ?";
    $params[] = $customer_name;
    $types .= "s";
}
if ($inspector_name != '') {
    $conditions[] = "inspector_name = ?";
    $params[] = $inspector_name;
    $types .= "s";
}
if ($project_status != '') {
    $conditions[] = "project_status = ?";
    $params[] = $project_status;
    $types .= "s";
}

$where = !empty($conditions) ? " AND " . implode(" AND ", $conditions) : "";

/* ---------------- EXPORT URL ---------------- */
$exportParams = $_GET;
unset($exportParams['page']);
$exportParams['export'] = 'csv';
$exportUrl = '?' . http_build_query($exportParams);

/* ---------------- CSV EXPORT ---------------- */
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=projects_kpi.csv");

    $out = fopen("php://output", "w");
    fputcsv($out, ["Project No", "Customer", "Inspector", "Status", "Creation Date"]);

    $sql = "SELECT project_no, customer_name, inspector_name, project_status, creation_date
            FROM project_info WHERE 1=1 $where ORDER BY creation_date DESC";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}

/* ---------------- PAGINATION ---------------- */
$limit = 10;
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$countSql = "SELECT COUNT(*) AS total FROM project_info WHERE 1=1 $where";
$stmt = $conn->prepare($countSql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$totalRecords = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = max(1, ceil($totalRecords / $limit));
$page = min($page, $totalPages);

/* ---------------- KPI ---------------- */
$kpiSql = "SELECT
    COUNT(*) AS total_projects,
    SUM(project_status='Completed') AS completed_projects,
    SUM(project_status!='Completed') AS pending_projects
FROM project_info WHERE 1=1 $where";

$stmt = $conn->prepare($kpiSql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$kpi = $stmt->get_result()->fetch_assoc();

/* ---------------- LIST ---------------- */
$listSql = "SELECT project_no, customer_name, inspector_name, project_status, creation_date
FROM project_info WHERE 1=1 $where
ORDER BY creation_date DESC LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($listSql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$listResult = $stmt->get_result();

/* ---------------- DROPDOWNS ---------------- */
$customers  = $conn->query("SELECT DISTINCT customer_name FROM project_info ORDER BY customer_name");
$inspectors = $conn->query("SELECT DISTINCT inspector_name FROM project_info ORDER BY inspector_name");
$years      = $conn->query("SELECT DISTINCT YEAR(creation_date) y FROM project_info ORDER BY y DESC");

$hasFilters = $year || $customer_name || $inspector_name || $project_status;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Projects KPI Dashboard</title>

<link rel="stylesheet" href="../assets/css/minified/main.min.css">

<style>
.pagination {
  display:flex;
  gap:4px;
  justify-content:center;
  flex-wrap:wrap;
}
.pagination .page-link {
  border-radius:6px;
  padding:6px 12px;
}
@media (max-width:576px) {
  .pagination .page-link {
    padding:5px 8px;
    font-size:13px;
  }
}
</style>
</head>

<body>

<div class="main-content">
  <div class="container-fluid">

    <!-- HEADER -->
    <div class="row mb-4">
      <div class="col-xl-12 d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1">Projects KPI Dashboard</h4>
          <p class="font-14 text-muted">Administrative overview</p>
        </div>
        <a href="<?= $exportUrl ?>" class="btn btn-outline-success">
          <i class="fa-solid fa-download"></i> Export CSV
        </a>
      </div>
    </div>

    <!-- FILTERS -->
    <div class="card mb-30">
      <div class="card-body">
        <form method="GET">
          <div class="row g-3">
            <div class="col-xl-2 col-md-4">
              <select name="year" class="form-control">
                <option value="">Year</option>
                <?php while ($y = $years->fetch_assoc()) { ?>
                  <option value="<?= $y['y'] ?>" <?= ($year==$y['y'])?'selected':'' ?>><?= $y['y'] ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-xl-3 col-md-4">
              <select name="customer_name" class="form-control">
                <option value="">Customer</option>
                <?php while ($c = $customers->fetch_assoc()) { ?>
                  <option value="<?= $c['customer_name'] ?>" <?= ($customer_name==$c['customer_name'])?'selected':'' ?>><?= $c['customer_name'] ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-xl-3 col-md-4">
              <select name="inspector_name" class="form-control">
                <option value="">Inspector</option>
                <?php while ($i = $inspectors->fetch_assoc()) { ?>
                  <option value="<?= $i['inspector_name'] ?>" <?= ($inspector_name==$i['inspector_name'])?'selected':'' ?>><?= $i['inspector_name'] ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-xl-2 col-md-4">
              <select name="project_status" class="form-control">
                <option value="">Status</option>
                <option value="Completed" <?= $project_status=='Completed'?'selected':'' ?>>Completed</option>
                <option value="Pending" <?= $project_status=='Pending'?'selected':'' ?>>Pending</option>
              </select>
            </div>

            <div class="col-xl-2 col-md-4 d-grid gap-2">
              <button class="btn btn-primary">
                <i class="fa-solid fa-filter"></i> Apply
              </button>
              <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>"
                 class="btn btn-outline-secondary <?= !$hasFilters?'disabled':'' ?>">
                <i class="fa-solid fa-rotate-left"></i> Clear
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- KPI CARDS -->
    <div class="row">
      <div class="col-xl-4 col-sm-6">
        <div class="card mb-30">
          <div class="state">
            <div class="d-flex align-items-center">
              <div class="state-icon"><i class="fa-solid fa-diagram-project fa-3x text-primary"></i></div>
              <div class="state-content">
                <p class="font-14 mb-2">Total Projects</p>
                <h2><?= $kpi['total_projects'] ?></h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-sm-6">
        <div class="card mb-30">
          <div class="state">
            <div class="d-flex align-items-center">
              <div class="state-icon"><i class="fa-solid fa-circle-check fa-3x text-success"></i></div>
              <div class="state-content">
                <p class="font-14 mb-2">Completed</p>
                <h2><?= $kpi['completed_projects'] ?></h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-sm-6">
        <div class="card mb-30">
          <div class="state">
            <div class="d-flex align-items-center">
              <div class="state-icon"><i class="fa-solid fa-hourglass-half fa-3x text-warning"></i></div>
              <div class="state-content">
                <p class="font-14 mb-2">Pending</p>
                <h2><?= $kpi['pending_projects'] ?></h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TABLE -->
    <div class="card mb-30">
      <div class="table-responsive">
        <table class="table style--three table-centered text-nowrap">
          <thead>
            <tr>
              <th>Project No</th>
              <th>Customer</th>
              <th>Inspector</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $listResult->fetch_assoc()) { ?>
              <tr>
                <td>#<?= $row['project_no'] ?></td>
                <td><?= $row['customer_name'] ?></td>
                <td><?= $row['inspector_name'] ?></td>
                <td>
                  <span class="badge <?= $row['project_status']=='Completed'?'badge-success':'badge-danger' ?>">
                    <?= $row['project_status'] ?>
                  </span>
                </td>
                <td><?= date('d M Y', strtotime($row['creation_date'])) ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- PAGINATION -->
    <nav class="mt-4">
      <ul class="pagination">
        <li class="page-item <?= $page<=1?'disabled':'' ?>">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>$page-1])) ?>">Prev</a>
        </li>

        <?php
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);
        if ($start > 1) {
          echo '<li class="page-item"><a class="page-link" href="?'.http_build_query(array_merge($_GET,['page'=>1])).'">1</a></li>';
          if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
        for ($i=$start;$i<=$end;$i++) {
          echo '<li class="page-item '.($i==$page?'active':'').'">
                  <a class="page-link" href="?'.http_build_query(array_merge($_GET,['page'=>$i])).'">'.$i.'</a>
                </li>';
        }
        if ($end < $totalPages) {
          if ($end < $totalPages-1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
          echo '<li class="page-item"><a class="page-link" href="?'.http_build_query(array_merge($_GET,['page'=>$totalPages])).'">'.$totalPages.'</a></li>';
        }
        ?>

        <li class="page-item <?= $page>=$totalPages?'disabled':'' ?>">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>$page+1])) ?>">Next</a>
        </li>
      </ul>
    </nav>

  </div>
</div>

</body>

<?php
include_once('../inc/footer.php');
?>
</html>
