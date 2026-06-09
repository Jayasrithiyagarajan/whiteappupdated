import re

with open('c:/xampp/htdocs/whiteapp1/document/loadtest/index.php', 'r', encoding='utf-8') as f:
    index_html = f.read()

# Add the filter-section back to index.php
filter_html = """    <!-- Filter Section -->
    <div class="filter-section">
        <div class="section-heading">
            <div>
                <h5>Load Test Filters</h5>
                <p>Refine by inspector, date, client, status, and year</p>
            </div>
            <button id="reset-filters" class="filter-toggle" type="button" onclick="clearLoadTestFilters()">
                <i class="icofont-refresh"></i> Reset
            </button>
        </div>

        <div class="filter-row">
            <div class="filter-item">
                <label>Inspector</label>
                <select id="filter-inspector" class="form-select">
                    <option value="">All Inspectors</option>
                    <?php 
                    $inspectors = $conn->query("SELECT DISTINCT inspector_name FROM loadtest_certificate WHERE inspector_name != '' ORDER BY inspector_name");
                    while($row = $inspectors->fetch_assoc()) echo "<option value='{$row['inspector_name']}'>{$row['inspector_name']}</option>";
                    ?>
                </select>
            </div>
            <div class="filter-item">
                <label>Date</label>
                <input type="date" id="filter-date" class="form-control">
            </div>
            <div class="filter-item">
                <label>Client</label>
                <select id="filter-client" class="form-select">
                    <option value="">All Clients</option>
                    <?php 
                    $clients = $conn->query("SELECT DISTINCT customer_name FROM loadtest_certificate WHERE customer_name != '' ORDER BY customer_name");
                    while($row = $clients->fetch_assoc()) echo "<option value='{$row['customer_name']}'>{$row['customer_name']}</option>";
                    ?>
                </select>
            </div>
            <div class="filter-item">
                <label>Status</label>
                <select id="filter-status" class="form-select">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Expired">Expired</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Year</label>
                <select id="filter-year" class="form-select">
                    <option value="">All Years</option>
                    <?php 
                    $years = $conn->query("SELECT DISTINCT YEAR(examination_date) as y FROM loadtest_certificate ORDER BY y DESC");
                    while($row = $years->fetch_assoc()) echo "<option value='{$row['y']}'>{$row['y']}</option>";
                    ?>
                </select>
            </div>
            <div class="filter-item">
                <button class="btn-clear" type="button" onclick="clearLoadTestFilters()">
                    <i class="icofont-close"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>
"""

# Inject filter_html right before <!-- Table Section -->
index_html = index_html.replace('<!-- Filter Section -->\n    <!-- Table Section -->', filter_html)

# Add clearLoadTestFilters logic to script
js_logic = """
$('.form-select, .form-control').on('change keyup', function() {
    $('#loadtest-table').DataTable().ajax.reload();
});

function clearLoadTestFilters() {
    $('.form-select, .form-control, #customSearch').val('');
    $('#loadtest-table').DataTable().search('');
    $('#loadtest-table').DataTable().ajax.reload();
}
"""
index_html = index_html.replace('function redirectToEditLoadTest', js_logic + '\nfunction redirectToEditLoadTest')

# Add data callback to Datatables AJAX and dataSrc for KPI
dt_ajax_replace = """        ajax: {
            url: "fetch_loadtest_certificates.php",
            type: "POST",
            data: function(d) {
                d.filter_inspector = $('#filter-inspector').val();
                d.filter_date = $('#filter-date').val();
                d.filter_client = $('#filter-client').val();
                d.filter_status = $('#filter-status').val();
                d.filter_year = $('#filter-year').val();
            },
            dataSrc: function(json) {
                $('#kpi-total').text(json.kpi.total);
                $('#kpi-active').text(json.kpi.active);
                $('#kpi-expired').text(json.kpi.expired);
                return json.data;
            }
        },"""

index_html = re.sub(r'ajax: \{.*?\},', dt_ajax_replace, index_html, flags=re.DOTALL)

with open('c:/xampp/htdocs/whiteapp1/document/loadtest/index.php', 'w', encoding='utf-8') as f:
    f.write(index_html)

# Now modify fetch_loadtest_certificates.php
with open('c:/xampp/htdocs/whiteapp1/document/loadtest/fetch_loadtest_certificates.php', 'r', encoding='utf-8') as f:
    fetch_php = f.read()

filters_injection = """
$filterInspector = $_POST['filter_inspector'] ?? '';
$filterDate = $_POST['filter_date'] ?? '';
$filterClient = $_POST['filter_client'] ?? '';
$filterStatus = $_POST['filter_status'] ?? '';
$filterYear = $_POST['filter_year'] ?? '';

if (!empty($filterInspector)) {
    $where .= " AND lc.inspector_name = '" . mysqli_real_escape_string($conn, $filterInspector) . "'";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(lc.examination_date) = '" . mysqli_real_escape_string($conn, $filterDate) . "'";
}
if (!empty($filterClient)) {
    $where .= " AND lc.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "'";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(lc.examination_date) = '" . mysqli_real_escape_string($conn, $filterYear) . "'";
}
if ($filterStatus === 'Active') {
    $where .= " AND (lc.latest_date_exam >= CURDATE() OR lc.latest_date_exam IS NULL OR lc.latest_date_exam = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND lc.latest_date_exam < CURDATE() AND lc.latest_date_exam != '0000-00-00' AND lc.latest_date_exam IS NOT NULL";
}

/* KPI counts */
$kpiSql = "
SELECT
    COUNT(DISTINCT project_no) AS total,
    COUNT(DISTINCT CASE WHEN latest_date_exam >= CURDATE() OR latest_date_exam IS NULL OR latest_date_exam = '0000-00-00' THEN project_no END) AS active,
    COUNT(DISTINCT CASE WHEN latest_date_exam < CURDATE() AND latest_date_exam != '0000-00-00' AND latest_date_exam IS NOT NULL THEN project_no END) AS expired
FROM loadtest_certificate lc
$where
";
$kpiRow = $conn->query($kpiSql)->fetch_assoc();
"""

fetch_php = fetch_php.replace('/* total */', filters_injection + '\n/* total */')

json_replace = """echo json_encode([
    "draw"=>$draw,
    "recordsTotal"=>$total,
    "recordsFiltered"=>$total,
    "kpi" => [
        "total"   => (int)$kpiRow['total'],
        "active"  => (int)$kpiRow['active'],
        "expired" => (int)$kpiRow['expired']
    ],
    "data"=>$data
]);"""

fetch_php = re.sub(r'echo json_encode\(\[\s*"draw".*?\]\);', json_replace, fetch_php, flags=re.DOTALL)

with open('c:/xampp/htdocs/whiteapp1/document/loadtest/fetch_loadtest_certificates.php', 'w', encoding='utf-8') as f:
    f.write(fetch_php)

print("Done")
