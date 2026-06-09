<?php
include_once('../inc/function.php');
include_once('../file/config.php');

// Initialize $inspector as null
$inspector = null;

// Check if `id` is provided in the query string
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    // Fetch the inspector details based on the provided ID
    if ($stmt = $conn->prepare("SELECT * FROM inspectors WHERE id = ?")) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the data if a row exists
        if ($result->num_rows > 0) {
            $inspector = $result->fetch_assoc();
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Inspector</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/premium-nav.css">

<style>
/* ===== PREMIUM DASHBOARD UI ===== */
body {
    background: #eef1f6;
    font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial;
}
.main-content {
    background: linear-gradient(180deg, #f6f8fb 0%, #eef1f6 100%);
    min-height: 100vh;
    padding-bottom: 50px;
}

.btn-back {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #475569;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02);
}
.btn-back:hover {
    background: #f8fafc;
    color: #1e293b;
    border-color: #cbd5e1;
    transform: translateY(-1px);
    text-decoration: none;
}

.profile-header-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    padding: 40px;
    text-align: center;
    border: 1px solid #f0f2f7;
    position: relative;
    overflow: hidden;
}

/* Decorative background inside profile card */
.profile-header-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 120px;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(37, 99, 235, 0.1));
    z-index: 0;
}

.profile-avatar-wrapper {
    position: relative;
    z-index: 1;
    display: inline-block;
    margin-top: 30px;
}

.profile-avatar {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #ffffff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.profile-name {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1e293b;
    margin-top: 15px;
    margin-bottom: 5px;
    position: relative;
    z-index: 1;
}

.badge-active {
    background: #dcfce7;
    color: #15803d;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    display: inline-block;
    position: relative;
    z-index: 1;
}

.info-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.04);
    border: 1px solid #f0f2f7;
    height: 100%;
    overflow: hidden;
}

.info-card-header {
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 12px;
}

.info-card-header h5 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
    font-size: 1.1rem;
}

.icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.bg-blue-light { background: #eff6ff; color: #2563eb; }
.bg-purple-light { background: #f3e8ff; color: #9333ea; }

.info-list {
    padding: 25px;
    margin: 0;
    list-style: none;
}

.info-item {
    display: flex;
    margin-bottom: 20px;
    align-items: flex-start;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    width: 120px;
    font-weight: 600;
    color: #64748b;
    font-size: 0.95rem;
}

.info-value {
    flex: 1;
    color: #1e293b;
    font-weight: 500;
    font-size: 0.95rem;
}

.crane-badge {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 10px 15px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #334155;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 10px;
    margin-right: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    transition: all 0.2s;
}

.crane-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.05);
    border-color: #cbd5e1;
}

.crane-icon {
    color: #f59e0b;
    font-size: 1.1rem;
}

@media(max-width: 768px) {
    .info-item { flex-direction: column; }
    .info-label { margin-bottom: 4px; }
}

</style>
</head>
<body>

<?php 
if (file_exists('../inc/nav.php')) {
    include_once('../inc/nav.php'); 
}
?>

<div class="main-content d-flex flex-column">
    <div class="container-fluid mt-4">
        
        <div class="mb-4">
            <a href="all-inspector.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <?php if ($inspector): ?>
            
            <?php
            $folder = strtolower(str_replace(' ', '_', $inspector['inspector_name']));
            $photo  = "./uploads/$folder/images/profile_image.jpg";
            if (!file_exists($photo)) {
                $photo = $url . "assets/img/img-placeholder.png";
            }
            ?>

            <div class="row">
                <!-- Left Column: Profile Card -->
                <div class="col-lg-4 mb-4">
                    <div class="profile-header-card">
                        <div class="profile-avatar-wrapper">
                            <img src="<?= $photo; ?>" alt="Profile Photo" class="profile-avatar">
                        </div>
                        <h4 class="profile-name"><?php echo htmlspecialchars($inspector['inspector_name']); ?></h4>
                        <div class="mt-2">
                            <span class="badge-active"><i class="fas fa-check-circle" style="margin-right: 5px;"></i> Active</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Details -->
                <div class="col-lg-8">
                    
                    <div class="row">
                        <!-- Personal Info -->
                        <div class="col-md-12 mb-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <div class="icon-box bg-blue-light">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <h5>Personal Information</h5>
                                </div>
                                <ul class="info-list">
                                    <li class="info-item">
                                        <div class="info-label">Email Address</div>
                                        <div class="info-value"><?php echo htmlspecialchars($inspector['email'] ?? 'N/A'); ?></div>
                                    </li>
                                    <li class="info-item">
                                        <div class="info-label">Phone Number</div>
                                        <div class="info-value"><?php echo htmlspecialchars($inspector['mobile'] ?? 'N/A'); ?></div>
                                    </li>
                                    <li class="info-item">
                                        <div class="info-label">City</div>
                                        <div class="info-value"><?php echo htmlspecialchars($inspector['city'] ?? 'N/A'); ?></div>
                                    </li>
                                    <li class="info-item">
                                        <div class="info-label">Address</div>
                                        <div class="info-value"><?php echo htmlspecialchars($inspector['address'] ?? 'N/A'); ?></div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Crane Management -->
                        <div class="col-md-12 mb-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <div class="icon-box bg-purple-light">
                                        <i class="fas fa-truck-pickup"></i>
                                    </div>
                                    <h5>Cranes Handled</h5>
                                </div>
                                <div class="p-4">
                                    <?php 
                                        if (!empty($inspector['handle_crane'])) {
                                            $cranes = unserialize($inspector['handle_crane']);
                                            if ($cranes && is_array($cranes)) {
                                                foreach ($cranes as $crane) {
                                                    $formatted_crane = ucwords(str_replace(['_', '-'], ' ', $crane));
                                                    echo '<div class="crane-badge">
                                                            <i class="fas fa-hard-hat crane-icon"></i> ' . $formatted_crane . '
                                                          </div>';
                                                }
                                            } else {
                                                echo '<span class="text-muted font-weight-500"><i class="fas fa-info-circle" style="margin-right: 5px;"></i> No cranes assigned.</span>';
                                            }
                                        } else {
                                            echo '<span class="text-muted font-weight-500"><i class="fas fa-info-circle" style="margin-right: 5px;"></i> No cranes assigned.</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-danger" style="border-radius: 12px; border: none; background: #fee2e2; color: #dc2626; padding: 20px;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i> Inspector not found.
            </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once('../inc/footer.php'); ?>
</body>
</html>