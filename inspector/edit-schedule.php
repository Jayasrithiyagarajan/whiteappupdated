<?php
session_start();
include_once('../inc/function.php');
include_once('../file/config.php');

$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: schedule-manager.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM inspector_schedules WHERE id = '$id'");
$schedule = mysqli_fetch_assoc($query);

if (!$schedule) {
    header("Location: schedule-manager.php");
    exit;
}

// Check permissions
if ($user_role == 'inspector') {
    $ins_query = mysqli_query($conn, "SELECT id FROM inspectors WHERE inspector_name = '$logged_in_user'");
    $ins_data = mysqli_fetch_assoc($ins_query);
    if ($ins_data['id'] != $schedule['inspector_id']) {
        echo "You do not have permission to edit this schedule.";
        exit;
    }
}
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-30">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-30">
                            <h4 class="font-20">Edit Schedule</h4>
                            <a href="schedule-manager.php" class="btn btn-outline-primary">Back to List</a>
                        </div>

                        <form id="editScheduleForm">
                            <input type="hidden" name="id" value="<?php echo $schedule['id']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-20">
                                    <label class="font-14 bold mb-2">Customer *</label>
                                    <select class="theme-input-style" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        <?php
                                        $customers_list_query = mysqli_query($conn, "SELECT id, customer_name FROM customers ORDER BY customer_name");
                                        while($cust = mysqli_fetch_assoc($customers_list_query)) {
                                            $selected = ($cust['id'] == $schedule['customer_id']) ? 'selected' : '';
                                            echo '<option value="'.$cust['id'].'" '.$selected.'>'.$cust['customer_name'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <label class="font-14 bold mb-2">Inspector *</label>
                                    <select class="theme-input-style" name="inspector_id" required>
                                        <option value="">Select Inspector</option>
                                        <?php
                                        $inspectors_list_query = mysqli_query($conn, "SELECT id, inspector_name FROM inspectors ORDER BY inspector_name");
                                        while($ins = mysqli_fetch_assoc($inspectors_list_query)) {
                                            $selected = ($ins['id'] == $schedule['inspector_id']) ? 'selected' : '';
                                            echo '<option value="'.$ins['id'].'" '.$selected.'>'.$ins['inspector_name'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-20">
                                    <label class="font-14 bold mb-2">Schedule Type *</label>
                                    <select class="theme-input-style" name="schedule_type" required>
                                        <option value="offshore" <?php echo $schedule['schedule_type'] == 'offshore' ? 'selected' : ''; ?>>Offshore Duty</option>
                                        <option value="onshore" <?php echo $schedule['schedule_type'] == 'onshore' ? 'selected' : ''; ?>>Onshore Duty</option>
                                        <option value="training" <?php echo $schedule['schedule_type'] == 'training' ? 'selected' : ''; ?>>Training</option>
                                        <option value="maintenance" <?php echo $schedule['schedule_type'] == 'maintenance' ? 'selected' : ''; ?>>Equipment Maintenance</option>
                                        <option value="emergency" <?php echo $schedule['schedule_type'] == 'emergency' ? 'selected' : ''; ?>>Emergency Response</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <label class="font-14 bold mb-2">Priority</label>
                                    <select class="theme-input-style" name="priority">
                                        <option value="low" <?php echo $schedule['priority'] == 'low' ? 'selected' : ''; ?>>Low</option>
                                        <option value="medium" <?php echo $schedule['priority'] == 'medium' ? 'selected' : ''; ?>>Medium</option>
                                        <option value="high" <?php echo $schedule['priority'] == 'high' ? 'selected' : ''; ?>>High</option>
                                        <option value="urgent" <?php echo $schedule['priority'] == 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-20">
                                    <label class="font-14 bold mb-2">Start Date & Time *</label>
                                    <input type="datetime-local" class="theme-input-style" name="start_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($schedule['start_datetime'])); ?>" required>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <label class="font-14 bold mb-2">End Date & Time *</label>
                                    <input type="datetime-local" class="theme-input-style" name="end_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($schedule['end_datetime'])); ?>" required>
                                </div>
                            </div>

                            <div class="mb-20">
                                <label class="font-14 bold mb-2">Location *</label>
                                <input type="text" class="theme-input-style" name="location" value="<?php echo htmlspecialchars($schedule['location']); ?>" required>
                            </div>

                            <div class="mb-20">
                                <label class="font-14 bold mb-2">Description</label>
                                <textarea class="theme-input-style" name="description" rows="3"><?php echo htmlspecialchars($schedule['description']); ?></textarea>
                            </div>

                            <div class="mb-20">
                                <label class="font-14 bold mb-2">Equipment/Assets</label>
                                <input type="text" class="theme-input-style" name="equipment" value="<?php echo htmlspecialchars($schedule['equipment']); ?>">
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-primary long" onclick="updateSchedule()">Update Schedule</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateSchedule() {
    const formData = $('#editScheduleForm').serialize();
    $.ajax({
        url: 'ajax/save_schedule.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            const res = JSON.parse(response);
            if (res.status === 'success') {
                alert('Schedule updated!');
                window.location.href = 'schedule-manager.php';
            } else {
                alert('Error: ' + res.message);
            }
        }
    });
}
</script>

<?php include_once('../inc/footer.php'); ?>
