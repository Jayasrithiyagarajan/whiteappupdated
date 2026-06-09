<?php 
// session_start();
include_once('../inc/function.php');
include_once('../file/config.php');

// Check if the user is logged in
$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}

// Get filter parameters
$filter_status = $_GET['status'] ?? 'all';
$filter_priority = $_GET['priority'] ?? 'all';
$filter_type = $_GET['type'] ?? 'all';
$filter_inspector = $_GET['inspector'] ?? 'all';
$filter_date = $_GET['date'] ?? '';
$current_customer = $_GET['customer'] ?? 'all';

// Build base query
$base_query = "
    SELECT s.*, i.inspector_name as inspector_display_name, i.mobile as inspector_phone
    FROM inspector_schedules s 
    LEFT JOIN inspectors i ON s.inspector_id = i.id 
    WHERE 1=1
";

// Apply filters
if ($filter_status != 'all') {
    $base_query .= " AND s.status = '$filter_status'";
}
if ($filter_priority != 'all') {
    $base_query .= " AND s.priority = '$filter_priority'";
}
if ($filter_type != 'all') {
    $base_query .= " AND s.schedule_type = '$filter_type'";
}
if ($filter_inspector != 'all') {
    $base_query .= " AND s.inspector_id = '$filter_inspector'";
}
if ($filter_date) {
    $base_query .= " AND DATE(s.start_datetime) = '$filter_date'";
}
if ($current_customer != 'all') {
    $base_query .= " AND s.customer_id = '$current_customer'";
}

// For inspectors, only show their own schedules
if ($user_role == 'inspector') {
    // We need to link username to inspector_id if possible, 
    // but assuming session might store the inspector id if we are lucky.
    // Let's check session variables again. In dashboard/index.php, it's just 'username'.
    // In whiteapp1, inspector_name seems to be used to identify them in project_info.
    $base_query .= " AND i.inspector_name = '$logged_in_user'";
}

$base_query .= " ORDER BY s.start_datetime DESC";

// Execute query
$schedules_query = mysqli_query($conn, $base_query);
$total_schedules = mysqli_num_rows($schedules_query);

// Get statistics
$stats_condition = "";
if ($user_role == 'inspector') {
    $stats_condition = " WHERE inspector_id IN (SELECT id FROM inspectors WHERE inspector_name = '$logged_in_user')";
}

if ($current_customer != 'all') {
    $stats_condition .= $stats_condition ? " AND customer_id = '$current_customer'" : " WHERE customer_id = '$current_customer'";
}

$stats_query = mysqli_query($conn, "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'scheduled' THEN 1 ELSE 0 END) as scheduled,
        SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
        SUM(CASE WHEN priority = 'urgent' THEN 1 ELSE 0 END) as urgent
    FROM inspector_schedules 
    $stats_condition
");
$stats = mysqli_fetch_assoc($stats_query);

// Get inspectors for filter
$inspectors_query = mysqli_query($conn, "SELECT id, inspector_name FROM inspectors ORDER BY inspector_name");

// Get customers for filter
$customers_query = mysqli_query($conn, "SELECT id, customer_name FROM customers ORDER BY customer_name");
?>


<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6045E2 0%, #8280FD 100%);
        --secondary-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
        --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        --danger-gradient: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --glass-bg: rgba(255, 255, 255, 0.9);
        --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    }

    .main-content {
        padding: 20px;
        background: #f8f9fa;
    }

    /* Header Styling */
    .page-header-box {
        background: white;
        border-radius: 15px;
        padding: 20px 25px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header-box h4 {
        margin: 0;
        font-weight: 700;
        color: #333;
        font-size: 1.5rem;
    }

    /* Stats Cards Styling */
    .stat-card {
        border: none;
        border-radius: 15px;
        padding: 20px;
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-card i {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.2;
    }

    .stat-card.total { background: var(--primary-gradient); }
    .stat-card.scheduled { background: var(--info-gradient); }
    .stat-card.progress { background: var(--warning-gradient); }
    .stat-card.completed { background: var(--secondary-gradient); }
    .stat-card.urgent { background: var(--danger-gradient); }
    .stat-card.cancelled { background: #6c757d; }

    .stat-card h3 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 5px;
        color: white;
    }

    .stat-card p {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.9;
    }

    /* Filter Styling */
    .filter-card {
        background: white;
        border-radius: 15px;
        box-shadow: var(--card-shadow);
        padding: 25px;
        margin-bottom: 30px;
        border: none;
    }

    .filter-card h5 {
        font-weight: 700;
        margin-bottom: 20px;
        color: #444;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .theme-input-style {
        border-radius: 8px !important;
        border: 1px solid #e0e0e0 !important;
        padding: 10px 15px !important;
        height: auto !important;
        font-size: 0.9rem !important;
        transition: all 0.3s ease !important;
    }

    .theme-input-style:focus {
        border-color: #6045E2 !important;
        box-shadow: 0 0 0 3px rgba(96, 69, 226, 0.1) !important;
    }

    /* Table Styling */
    .table-card {
        background: white;
        border-radius: 15px;
        box-shadow: var(--card-shadow);
        border: none;
        overflow: hidden;
    }

    .table thead th {
        background: #f8faff;
        border-top: none;
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 15px 20px;
    }

    .table tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        border-color: #f1f1f1;
    }

    .inspector-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f0f0ff;
        color: #6045E2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .badge-scheduled { background: #e3f2fd; color: #1976d2; }
    .badge-in_progress { background: #fff3e0; color: #f57c00; }
    .badge-completed { background: #e8f5e9; color: #388e3c; }
    .badge-cancelled { background: #ffebee; color: #d32f2f; }

    /* Responsive Table for Mobile */
    @media (max-width: 991px) {
        .table-responsive-mobile table, 
        .table-responsive-mobile thead, 
        .table-responsive-mobile tbody, 
        .table-responsive-mobile th, 
        .table-responsive-mobile td, 
        .table-responsive-mobile tr { 
            display: block; 
        }
        
        .table-responsive-mobile thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        
        .table-responsive-mobile tr { 
            border: 1px solid #eee;
            margin-bottom: 15px;
            border-radius: 12px;
            padding: 10px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        
        .table-responsive-mobile td { 
            border: none;
            position: relative;
            padding-left: 50% !important; 
            text-align: right;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        
        .table-responsive-mobile td:before { 
            position: absolute;
            left: 15px;
            width: 45%; 
            padding-right: 10px; 
            white-space: nowrap;
            text-align: left;
            font-weight: 700;
            color: #777;
            content: attr(data-label);
        }
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    }

    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 20px 30px;
    }

    .modal-body {
        padding: 30px;
    }

    .modal-footer {
        border-top: 1px solid #f0f0f0;
        padding: 20px 30px;
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="page-header-box">
            <h4><i class="icofont-calendar text-primary mr-2"></i> Schedule Manager</h4>
            <button class="btn btn-primary radius-50 px-4 py-2" data-toggle="modal" data-target="#addScheduleModal">
                <i class="icofont-plus-circle mr-1"></i> Add New Schedule
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-30">
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="stat-card total">
                    <h3><?php echo $stats['total'] ?? 0; ?></h3>
                    <p>Total Tasks</p>
                    <i class="icofont-listing-box"></i>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="stat-card scheduled">
                    <h3><?php echo $stats['scheduled'] ?? 0; ?></h3>
                    <p>Scheduled</p>
                    <i class="icofont-clock-time"></i>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="stat-card progress">
                    <h3><?php echo $stats['in_progress'] ?? 0; ?></h3>
                    <p>In Progress</p>
                    <i class="icofont-spinner-alt-3"></i>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="stat-card completed">
                    <h3><?php echo $stats['completed'] ?? 0; ?></h3>
                    <p>Completed</p>
                    <i class="icofont-check-circled"></i>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="stat-card urgent">
                    <h3><?php echo $stats['urgent'] ?? 0; ?></h3>
                    <p>Urgent Action</p>
                    <i class="icofont-warning"></i>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="stat-card cancelled">
                    <h3><?php echo $stats['cancelled'] ?? 0; ?></h3>
                    <p>Cancelled</p>
                    <i class="icofont-close-circled"></i>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <h5><i class="icofont-filter text-primary"></i> Advanced Filters</h5>
            <form id="filterForm">
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="font-12 bold text-uppercase text-muted mb-2">Status</label>
                        <select class="theme-input-style" id="filterStatus" name="status">
                            <option value="all">All Status</option>
                            <option value="scheduled" <?php echo $filter_status == 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                            <option value="in_progress" <?php echo $filter_status == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="completed" <?php echo $filter_status == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo $filter_status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="font-12 bold text-uppercase text-muted mb-2">Priority</label>
                        <select class="theme-input-style" id="filterPriority" name="priority">
                            <option value="all">All Priority</option>
                            <option value="low" <?php echo $filter_priority == 'low' ? 'selected' : ''; ?>>Low</option>
                            <option value="medium" <?php echo $filter_priority == 'medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="high" <?php echo $filter_priority == 'high' ? 'selected' : ''; ?>>High</option>
                            <option value="urgent" <?php echo $filter_priority == 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="font-12 bold text-uppercase text-muted mb-2">Type</label>
                        <select class="theme-input-style" id="filterType" name="type">
                            <option value="all">All Types</option>
                            <option value="offshore" <?php echo $filter_type == 'offshore' ? 'selected' : ''; ?>>Offshore</option>
                            <option value="onshore" <?php echo $filter_type == 'onshore' ? 'selected' : ''; ?>>Onshore</option>
                            <option value="training" <?php echo $filter_type == 'training' ? 'selected' : ''; ?>>Training</option>
                            <option value="maintenance" <?php echo $filter_type == 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                            <option value="emergency" <?php echo $filter_type == 'emergency' ? 'selected' : ''; ?>>Emergency</option>
                        </select>
                    </div>
                    <?php if($user_role == 'admin'): ?>
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="font-12 bold text-uppercase text-muted mb-2">Inspector</label>
                        <select class="theme-input-style" id="filterInspector" name="inspector">
                            <option value="all">All Inspectors</option>
                            <?php while($inspector = mysqli_fetch_assoc($inspectors_query)): ?>
                            <option value="<?php echo $inspector['id']; ?>" <?php echo $filter_inspector == $inspector['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($inspector['inspector_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="font-12 bold text-uppercase text-muted mb-2">Customer</label>
                        <select class="theme-input-style" id="filterCustomer" name="customer">
                            <option value="all">All Customers</option>
                            <?php while($customer = mysqli_fetch_assoc($customers_query)): ?>
                            <option value="<?php echo $customer['id']; ?>" <?php echo $current_customer == $customer['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($customer['customer_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="font-12 bold text-uppercase text-muted mb-2">Date</label>
                        <input type="date" class="theme-input-style" id="filterDate" name="date" value="<?php echo $filter_date; ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 d-flex justify-content-end gap-2">
                         <button type="button" class="btn btn-primary px-4" onclick="applyFilters()">Apply Filters</button>
                         <button type="button" class="btn btn-outline-secondary ml-2" onclick="clearFilters()">Reset</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Schedules Table -->
        <div class="table-card">
            <div class="p-0">
                <div class="table-responsive table-responsive-mobile">
                    <table class="table table-hover mb-0" id="schedulesTable">
                        <thead>
                            <tr>
                                <th>Inspector</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th>Schedule</th>
                                <th>Location</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($total_schedules > 0): ?>
                                <?php while($schedule = mysqli_fetch_assoc($schedules_query)): 
                                    $is_past = strtotime($schedule['end_datetime']) < time();
                                    $is_ongoing = strtotime($schedule['start_datetime']) <= time() && strtotime($schedule['end_datetime']) >= time();
                                ?>
                                <tr class="<?php echo $is_ongoing ? 'table-primary' : ($is_past ? 'text-muted' : ''); ?>">
                                    <td data-label="Inspector">
                                        <div class="d-flex align-items-center">
                                            <div class="inspector-avatar mr-3">
                                                <?php echo strtoupper(substr($schedule['inspector_display_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="font-14 bold"><?php echo htmlspecialchars($schedule['inspector_display_name']); ?></div>
                                                <small class="text-muted"><i class="icofont-phone mr-1"></i><?php echo $schedule['inspector_phone']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Customer">
                                        <div class="font-14 bold text-dark"><?php echo htmlspecialchars($schedule['customer_name']); ?></div>
                                    </td>
                                    <td data-label="Type">
                                        <span class="badge badge-light border text-capitalize"><?php echo $schedule['schedule_type']; ?></span>
                                    </td>
                                    <td data-label="Schedule">
                                        <div class="font-13 bold"><i class="icofont-calendar mr-1"></i><?php echo date('M j, Y', strtotime($schedule['start_datetime'])); ?></div>
                                        <div class="font-11 text-muted">
                                            <i class="icofont-clock-time mr-1"></i><?php echo date('g:i A', strtotime($schedule['start_datetime'])); ?> - 
                                            <?php echo date('g:i A', strtotime($schedule['end_datetime'])); ?>
                                        </div>
                                    </td>
                                    <td data-label="Location">
                                        <div class="font-13"><i class="icofont-location-pin text-danger mr-1"></i><?php echo htmlspecialchars($schedule['location']); ?></div>
                                    </td>
                                    <td data-label="Priority">
                                        <?php 
                                            $p_class = 'badge-secondary';
                                            switch($schedule['priority']) {
                                                case 'low': $p_class = 'badge-secondary'; break;
                                                case 'medium': $p_class = 'badge-info'; break;
                                                case 'high': $p_class = 'badge-warning text-dark'; break;
                                                case 'urgent': $p_class = 'badge-danger'; break;
                                            }
                                        ?>
                                        <span class="badge <?php echo $p_class; ?> text-uppercase"><?php echo $schedule['priority']; ?></span>
                                    </td>
                                    <td data-label="Status">
                                        <?php 
                                            $s_class = 'badge-scheduled';
                                            switch($schedule['status']) {
                                                case 'scheduled': $s_class = 'badge-scheduled'; break;
                                                case 'in_progress': $s_class = 'badge-in_progress'; break;
                                                case 'completed': $s_class = 'badge-completed'; break;
                                                case 'cancelled': $s_class = 'badge-cancelled'; break;
                                            }
                                        ?>
                                        <span class="badge <?php echo $s_class; ?>"><?php echo ucfirst(str_replace('_', ' ', $schedule['status'])); ?></span>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="d-flex justify-content-end justify-content-lg-start">
                                            <a href="edit-schedule.php?id=<?php echo $schedule['id']; ?>" class="btn btn-sm btn-outline-primary mr-2" title="Edit"><i class="icofont-edit"></i></a>
                                            <button onclick="deleteSchedule(<?php echo $schedule['id']; ?>)" class="btn btn-sm btn-outline-danger" title="Delete"><i class="icofont-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="icofont-calendar-alt text-muted" style="font-size: 4rem;"></i>
                                            <p class="mt-3 text-muted">No schedules found matching your filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">Add New Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addScheduleForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-14 bold mb-2">Customer <span class="text-danger">*</span></label>
                            <select class="theme-input-style w-100" name="customer_id" required>
                                <option value="">Select Customer</option>
                                <?php
                                mysqli_data_seek($customers_query, 0);
                                while($cust = mysqli_fetch_assoc($customers_query)) {
                                    echo '<option value="'.$cust['id'].'">'.$cust['customer_name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-14 bold mb-2">Inspector <span class="text-danger">*</span></label>
                            <select class="theme-input-style w-100" name="inspector_id" required>
                                <option value="">Select Inspector</option>
                                <?php
                                mysqli_data_seek($inspectors_query, 0);
                                while($ins = mysqli_fetch_assoc($inspectors_query)) {
                                    echo '<option value="'.$ins['id'].'">'.$ins['inspector_name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-14 bold mb-2">Schedule Type <span class="text-danger">*</span></label>
                            <select class="theme-input-style w-100" name="schedule_type" required>
                                <option value="offshore">Offshore Duty</option>
                                <option value="onshore">Onshore Duty</option>
                                <option value="training">Training</option>
                                <option value="maintenance">Equipment Maintenance</option>
                                <option value="emergency">Emergency Response</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-14 bold mb-2">Priority</label>
                            <select class="theme-input-style w-100" name="priority">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-14 bold mb-2">Start Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="theme-input-style w-100" name="start_datetime" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-14 bold mb-2">End Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="theme-input-style w-100" name="end_datetime" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="font-14 bold mb-2">Location <span class="text-danger">*</span></label>
                        <input type="text" class="theme-input-style w-100" name="location" placeholder="e.g., Offshore Platform A, Ras Tanura" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="font-14 bold mb-2">Description</label>
                        <textarea class="theme-input-style w-100" name="description" rows="3" placeholder="Describe the schedule details..."></textarea>
                    </div>
                    
                    <div class="mb-0">
                        <label class="font-14 bold mb-2">Equipment/Assets</label>
                        <input type="text" class="theme-input-style w-100" name="equipment" placeholder="e.g., Crane X-123, Safety Gear">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light radius-50 px-4" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary radius-50 px-4" onclick="saveSchedule()">Save Schedule</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Check if DataTable is already initialized and destroy it to avoid errors if needed
    if ($.fn.DataTable.isDataTable('#schedulesTable')) {
        $('#schedulesTable').DataTable().destroy();
    }

    $('#schedulesTable').DataTable({
        "order": [[3, 'desc']],
        "pageLength": 10,
        "responsive": true,
        "dom": '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Search schedules...",
            "lengthMenu": "Show _MENU_ entries",
        }
    });
});

function applyFilters() {
    const status = $('#filterStatus').val();
    const priority = $('#filterPriority').val();
    const type = $('#filterType').val();
    const inspector = $('#filterInspector').val() || 'all';
    const customer = $('#filterCustomer').val();
    const date = $('#filterDate').val();
    
    let url = 'schedule-manager.php?';
    const params = [];
    
    if (status !== 'all') params.push(`status=${status}`);
    if (priority !== 'all') params.push(`priority=${priority}`);
    if (type !== 'all') params.push(`type=${type}`);
    if (inspector !== 'all') params.push(`inspector=${inspector}`);
    if (customer !== 'all') params.push(`customer=${customer}`);
    if (date) params.push(`date=${date}`);
    
    window.location.href = url + params.join('&');
}

function clearFilters() {
    window.location.href = 'schedule-manager.php';
}

function deleteSchedule(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6045E2',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/delete_schedule.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    try {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            Swal.fire('Deleted!', 'Schedule has been deleted.', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    } catch(e) {
                        location.reload();
                    }
                }
            });
        }
    });
}

function saveSchedule() {
    const form = document.getElementById('addScheduleForm');
    if(!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = $('#addScheduleForm').serialize();
    $.ajax({
        url: 'ajax/save_schedule.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            try {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire('Saved!', 'New schedule has been added.', 'success')
                    .then(() => location.reload());
                } else {
                    Swal.fire('Error!', res.message, 'error');
                }
            } catch(e) {
                location.reload();
            }
        }
    });
}
</script>


<?php include_once('../inc/footer.php'); ?>
