<?php 
//session_start(); // Start session for messages
include_once('../inc/function.php');
include_once('../file/config.php');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture and sanitize form data
    $sticker_start_no = $_POST['sticker_start_no'];
    $assign_inspector = htmlspecialchars($_POST['assign_inspector']);
    $no_of_stickers = (int) $_POST['no_of_stickers'];

    // Variable for single sticker count
    $single_sticker_count = 1;
    $created_count = 0;
    $skipped_count = 0;

    for ($i = 0; $i < $no_of_stickers; $i++) {
        $current_sticker_no = $sticker_start_no + $i;

        // Check if sticker exists
        $check_sql = "SELECT 1 FROM stickers WHERE sticker_start_no = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $current_sticker_no);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $skipped_count++;
            $check_stmt->close();
            continue;
        }
        $check_stmt->close();

        // Insert new sticker
        $sql = "INSERT INTO stickers (sticker_start_no, assign_inspector, no_of_stickers) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $current_sticker_no, $assign_inspector, $single_sticker_count);

        if ($stmt->execute()) {
            $created_count++;
        }
        $stmt->close();
    }

    // Store success message in session
    $_SESSION['success_message'] = "Successfully created $created_count stickers.";
    if ($skipped_count > 0) {
        $_SESSION['success_message'] .= " $skipped_count stickers were skipped (already exist).";
    }
    
    // Redirect to self to prevent form resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Fetch inspector names from the database
$sql = "SELECT inspector_name FROM inspectors";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bulk Sticker Create</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/premium-nav.css">
<link rel="stylesheet" href="../assets/plugins/select2/select2.min.css">

<style>
/* ===== PREMIUM GLASSMORPHISM UI ===== */
body {
    background:
        radial-gradient(circle at 14% 8%, rgba(99, 102, 241, 0.12), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(168, 85, 247, 0.1), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
    font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    color: #1e293b;
}

.main-content {
    min-height: calc(100vh - 110px);
    display: flex;
    align-items: center;
    position: relative;
    padding: 40px 10px;
}

.sticker-glass-card {
    border: 1px solid rgba(255, 255, 255, 0.64);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.48));
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 28px;
    overflow: hidden;
    width: 100%;
}

.card-header-premium {
    padding: 35px 35px 20px;
    border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    text-align: center;
}

.card-header-premium .header-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 18px;
    box-shadow: 0 12px 24px rgba(99, 102, 241, 0.25);
}

.card-header-premium h4 {
    font-weight: 850;
    font-size: 1.8rem;
    color: #111827;
    margin-bottom: 8px;
}

.card-body-premium {
    padding: 40px;
}

.form-group label {
    font-weight: 800;
    color: #334155;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
}

/* Premium Input Styling */
.input-group-premium {
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 16px;
    display: flex;
    align-items: center;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: inset 0 2px 4px rgba(15, 23, 42, 0.02);
}

.input-group-premium:focus-within {
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), 0 4px 12px rgba(99, 102, 241, 0.05);
    transform: translateY(-1px);
}

.input-group-text-premium {
    padding: 0 20px;
    color: #94a3b8;
    font-size: 18px;
}

.form-control-premium {
    border: none;
    background: transparent;
    padding: 16px 20px 16px 0;
    width: 100%;
    outline: none;
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
}

.info-text {
    font-size: 0.8rem;
    color: #6366f1;
    font-weight: 700;
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Select2 Overrides */
.select2-container--default .select2-selection--single {
    background: transparent !important;
    border: none !important;
    height: 54px !important;
    display: flex !important;
    align-items: center !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    font-weight: 600 !important;
    color: #1e293b !important;
    padding-left: 0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 54px !important;
}

.btn-premium-pre {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: white !important;
    border: none;
    border-radius: 16px;
    padding: 18px;
    font-size: 1.1rem;
    font-weight: 800;
    width: 100%;
    transition: all 0.3s;
    box-shadow: 0 16px 32px rgba(99, 102, 241, 0.24);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-premium-pre:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 42px rgba(99, 102, 241, 0.3);
    filter: brightness(1.05);
}

.hero-text h1 {
    font-weight: 850;
    color: #111827;
    font-size: clamp(2.5rem, 4vw, 3.5rem);
    line-height: 1.1;
    margin-bottom: 24px;
}

.hero-text p {
    font-size: 1.15rem;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 32px;
}

.hero-feature {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
}

.hero-feature i {
    width: 28px;
    height: 28px;
    background: #dcfce7;
    color: #166534;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    margin-top: 4px;
    margin-right: 16px;
    flex: 0 0 auto;
}

.hero-feature span {
    font-size: 1.05rem;
    font-weight: 600;
    color: #334155;
}

@media(max-width: 991px) {
    .hero-text {
        text-align: center;
        margin-bottom: 50px;
    }
    .hero-feature {
        justify-content: center;
    }
}
</style>
</head>
<body>

<?php 
if (file_exists('../inc/nav.php')) {
    include_once('../inc/nav.php'); 
}
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-center" style="max-width: 1300px; margin: 0 auto;">
            
            <!-- Left Side Hero -->
            <div class="col-lg-6 hero-text">
                <h1>Manage Your <br><span style="background: linear-gradient(135deg, #6366f1, #4f46e5); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Sticker Inventory</span></h1>
                <p>Generate and assign sequential inspection stickers in bulk with precision and clarity.</p>
                
                <div class="hero-feature">
                    <i class="fas fa-check"></i>
                    <span>Assign massive ranges to specific inspectors in seconds.</span>
                </div>
                <div class="hero-feature">
                    <i class="fas fa-check"></i>
                    <span>Real-time duplicate detection ensures sequential integrity.</span>
                </div>
                <div class="hero-feature">
                    <i class="fas fa-check"></i>
                    <span>Optimized for high-volume logistics and field operations.</span>
                </div>
            </div>

            <!-- Right Side Card -->
            <div class="col-lg-5">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 18px; background: #dcfce7; color: #166534; font-weight: 700; padding: 20px;">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?php 
                        echo $_SESSION['success_message']; 
                        unset($_SESSION['success_message']); 
                        ?>
                    </div>
                <?php endif; ?>

                <div class="sticker-glass-card">
                    <div class="card-header-premium">
                        <div class="header-icon"><i class="fas fa-tags"></i></div>
                        <h4>Bulk Creation</h4>
                        <p class="text-muted font-weight-bold small">GENERATE SEQUENTIAL STICKERS</p>
                    </div>
                    
                    <div class="card-body-premium">
                        <form method="POST">
                            <!-- Sticker Start No -->
                            <div class="form-group mb-4">
                                <label>Sticker Starting Number</label>
                                <div class="input-group-premium">
                                    <div class="input-group-text-premium"><i class="fas fa-hashtag"></i></div>
                                    <?php 
                                    $last_sql = "SELECT MAX(CAST(sticker_start_no AS UNSIGNED)) as max_val FROM stickers";
                                    $last_res = mysqli_query($conn, $last_sql);
                                    $last_row = mysqli_fetch_assoc($last_res);
                                    $last_sticker_no = $last_row['max_val'] ?? 0;
                                    $next_sticker_no = $last_sticker_no + 1;
                                    ?>
                                    <input type="number" name="sticker_start_no" class="form-control-premium" value="<?= $next_sticker_no ?>" required>
                                </div>
                                <div class="info-text">
                                    <i class="fas fa-history"></i> Last Issued: <?= $last_sticker_no ?>
                                </div>
                            </div>

                            <!-- Inspector -->
                            <div class="form-group mb-4">
                                <label>Assign To Inspector</label>
                                <div class="input-group-premium">
                                    <div class="input-group-text-premium"><i class="fas fa-user-shield"></i></div>
                                    <select name="assign_inspector" class="form-control-premium select2-basic" required>
                                        <option value="">Select Inspector</option>
                                        <?php
                                        if ($result && mysqli_num_rows($result) > 0) {
                                            mysqli_data_seek($result, 0); 
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<option value="' . htmlspecialchars($row['inspector_name']) . '">' . htmlspecialchars($row['inspector_name']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="form-group mb-5">
                                <label>Quantity to Generate</label>
                                <div class="input-group-premium">
                                    <div class="input-group-text-premium"><i class="fas fa-layer-group"></i></div>
                                    <input type="number" name="no_of_stickers" class="form-control-premium" placeholder="e.g. 100" min="1" required>
                                </div>
                            </div>

                            <button type="submit" class="btn-premium-pre">
                                <i class="fas fa-plus-circle"></i> Generate Stickers
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/plugins/select2/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-basic').select2({
        width: '100%',
        dropdownParent: $('.sticker-glass-card')
    });
});
</script>

<?php 
include_once('../inc/footer.php');
?>
</body>
</html>