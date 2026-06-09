<?php 
include_once('../file/config.php');

$cus_name = "";
$profilePhoto = "";
$customer_id = "";
$mobile = "N/A";
$email = "N/A";
$rep_name = "N/A";
$address = "N/A";
$city = "N/A";
$date_of_adding = "N/A";
$reference_by = "N/A";
$notes = "";

if (isset($_GET['cusid'])) {
    $customer_id = $_GET['cusid'];

    $sql = "SELECT * FROM customers WHERE cus_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cus_name = $row['customer_name'];
        $mobile = $row['mobile'] ?: 'N/A';
        $email = $row['email'] ?: 'N/A';
        $profilePhoto = $row['profile_photo'] ?: $url . 'assets/img/media/profile-pic.jpg';
        $rep_name = $row['rep_name'] ?: 'N/A';
        $address = $row['address'] ?: 'N/A';
        $city = $row['city'] ?: 'N/A';
        $date_of_adding = $row['date_of_adding'] ?: 'N/A';
        $reference_by = $row['reference_by'] ?: 'N/A';
        $notes = $row['notes'] ?? '';
    } else {
        echo "<script>alert('No customer found with ID: $customer_id'); window.location.href='customer-list.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Customer ID not provided.'); window.location.href='customer-list.php';</script>";
    exit;
}

// Include header/nav
include_once('../inc/customer-option.php');
if (file_exists('../inc/nav.php')) {
    include_once('../inc/nav.php'); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Customer - <?php echo htmlspecialchars($cus_name); ?></title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/premium-nav.css">

<style>
:root {
    --client-ink: #17211f;
    --client-muted: #63706d;
    --client-line: rgba(23, 33, 31, 0.1);
    --client-mint: #14b8a6;
    --client-coral: #f97316;
    --client-paper: rgba(255, 255, 255, 0.78);
}

body {
    background:
        radial-gradient(circle at 7% 12%, rgba(20, 184, 166, 0.2), transparent 30%),
        radial-gradient(circle at 92% 18%, rgba(249, 115, 22, 0.16), transparent 26%),
        linear-gradient(135deg, #f8faf8 0%, #eef6f4 44%, #f7f0eb 100%);
    font-family: "Inter", "PT Sans", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    color: var(--client-ink);
}

.main-content.client-dossier {
    min-height: 100vh;
    padding: 26px 18px 56px;
}

.client-shell {
    max-width: 1380px;
    margin: 0 auto;
}

.client-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 22px;
}

.btn-back {
    min-height: 44px;
    padding: 10px 16px;
    border: 1px solid var(--client-line);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.72);
    color: var(--client-ink);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 800;
    text-decoration: none;
    box-shadow: 0 12px 28px rgba(23, 33, 31, 0.08);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}

.btn-back:hover {
    background: #fff;
    color: var(--client-ink);
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 18px 36px rgba(23, 33, 31, 0.12);
}

.client-status-pill {
    min-height: 42px;
    padding: 9px 14px;
    border-radius: 999px;
    background: rgba(20, 184, 166, 0.12);
    color: #0f766e;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    font-weight: 850;
    border: 1px solid rgba(20, 184, 166, 0.22);
}

.dossier-grid {
    display: grid;
    grid-template-columns: minmax(280px, 390px) minmax(0, 1fr);
    gap: 24px;
    align-items: stretch;
}

.identity-panel {
    position: relative;
    overflow: hidden;
    min-height: 640px;
    border-radius: 28px;
    padding: 28px;
    color: #fff;
    background:
        linear-gradient(160deg, rgba(23, 33, 31, 0.96), rgba(23, 33, 31, 0.86)),
        linear-gradient(135deg, rgba(20, 184, 166, 0.9), rgba(249, 115, 22, 0.78));
    box-shadow: 0 28px 80px rgba(23, 33, 31, 0.28);
}

.identity-panel:before {
    content: "";
    position: absolute;
    inset: auto -90px -90px auto;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: rgba(20, 184, 166, 0.22);
}

.identity-panel:after {
    content: "";
    position: absolute;
    inset: -70px auto auto -80px;
    width: 210px;
    height: 210px;
    border-radius: 50%;
    background: rgba(249, 115, 22, 0.18);
}

.identity-inner {
    position: relative;
    z-index: 1;
    min-height: 100%;
    display: flex;
    flex-direction: column;
}

.dossier-label {
    color: rgba(255, 255, 255, 0.68);
    font-size: 12px;
    font-weight: 900;
    letter-spacing: .16em;
    text-transform: uppercase;
}

.profile-avatar-wrapper {
    width: 154px;
    height: 154px;
    margin: 42px auto 24px;
    border-radius: 36px;
    padding: 8px;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.24);
    box-shadow: 0 22px 42px rgba(0, 0, 0, 0.24);
}

.profile-avatar {
    width: 100%;
    height: 100%;
    border-radius: 28px;
    object-fit: cover;
    background: #fff;
}

.profile-name {
    margin: 0;
    font-size: clamp(30px, 3vw, 44px);
    line-height: 1.05;
    font-weight: 900;
    letter-spacing: 0;
    text-align: center;
    overflow-wrap: anywhere;
    color:rgba(255, 255, 255, 0.62)
}

.profile-id {
    width: fit-content;
    margin: 18px auto 0;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.86);
    font-weight: 850;
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-top: auto;
    padding-top: 42px;
}

.quick-action {
    min-height: 78px;
    padding: 14px;
    border-radius: 18px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 8px;
    color: #fff;
    text-decoration: none;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.16);
    transition: transform .2s ease, background .2s ease;
}

.quick-action:hover {
    color: #fff;
    text-decoration: none;
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.16);
}

.quick-action i {
    color: #7dd3fc;
}

.quick-action span {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.62);
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
}

.quick-action strong {
    font-size: 13px;
    font-weight: 850;
    overflow-wrap: anywhere;
}

.detail-panel {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.hero-strip,
.detail-card {
    border: 1px solid rgba(255, 255, 255, 0.72);
    background: var(--client-paper);
    box-shadow: 0 24px 60px rgba(23, 33, 31, 0.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.hero-strip {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 18px;
    border-radius: 28px;
    padding: 28px;
}

.hero-strip h1 {
    margin: 0 0 10px;
    font-size: clamp(28px, 3vw, 44px);
    line-height: 1.08;
    font-weight: 900;
    color: var(--client-ink);
}

.hero-strip p {
    margin: 0;
    color: var(--client-muted);
    font-size: 15px;
    line-height: 1.55;
    max-width: 720px;
}

.mini-metric {
    min-width: 150px;
    padding: 16px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid var(--client-line);
}

.mini-metric span {
    display: block;
    color: var(--client-muted);
    font-size: 12px;
    font-weight: 900;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.mini-metric strong {
    display: block;
    margin-top: 6px;
    color: var(--client-ink);
    font-size: 18px;
    font-weight: 900;
}

.bento-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.detail-card {
    border-radius: 24px;
    padding: 24px;
    min-height: 220px;
}

.detail-card.wide {
    grid-column: 1 / -1;
}

.card-title-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.card-icon {
    width: 46px;
    height: 46px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #0f766e;
    background: rgba(20, 184, 166, 0.12);
}

.card-title-row h5 {
    margin: 0;
    color: var(--client-ink);
    font-size: 17px;
    font-weight: 900;
}

.detail-list {
    display: grid;
    gap: 14px;
}

.detail-item {
    display: grid;
    gap: 4px;
}

.detail-item span {
    color: var(--client-muted);
    font-size: 12px;
    font-weight: 900;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.detail-item strong,
.detail-item a {
    color: var(--client-ink);
    font-size: 15px;
    font-weight: 850;
    overflow-wrap: anywhere;
}

.detail-item a {
    color: #0f766e;
    text-decoration: none;
}

.detail-item a:hover {
    text-decoration: underline;
}

.note-box {
    min-height: 108px;
    padding: 18px;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.72);
    border: 1px solid var(--client-line);
    color: var(--client-muted);
    line-height: 1.6;
    font-weight: 700;
}

@media(max-width: 1100px) {
    .dossier-grid {
        grid-template-columns: 1fr;
    }

    .identity-panel {
        min-height: auto;
    }
}

@media(max-width: 768px) {
    .main-content.client-dossier {
        padding: 18px 10px 40px;
    }

    .client-topbar,
    .hero-strip {
        flex-direction: column;
        align-items: stretch;
    }

    .bento-grid,
    .quick-actions {
        grid-template-columns: 1fr;
    }

    .identity-panel,
    .hero-strip,
    .detail-card {
        border-radius: 22px;
        padding: 22px;
    }
}
</style>
</head>

<body>

<div class="main-content client-dossier">
    <div class="client-shell">
        <div class="client-topbar">
            <a href="customer-list.php" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Customers</span>
            </a>
            <div class="client-status-pill">
                <i class="fas fa-circle-check"></i>
                Active Customer
            </div>
        </div>

        <div class="dossier-grid">
            <aside class="identity-panel">
                <div class="identity-inner">
                    <div class="dossier-label">Customer Dossier</div>
                    <div class="profile-avatar-wrapper">
                        <img src="<?php echo $profilePhoto . '?v=' . time(); ?>" alt="Profile Photo" class="profile-avatar">
                    </div>
                    <h2 class="profile-name"><?php echo htmlspecialchars($cus_name); ?></h2>
                    <div class="profile-id"><?php echo htmlspecialchars($customer_id); ?></div>

                    <div class="quick-actions">
                        <a class="quick-action" href="<?php echo $email !== 'N/A' ? 'mailto:' . htmlspecialchars($email) : '#'; ?>">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                            <strong><?php echo htmlspecialchars($email !== 'N/A' ? $email : 'Not Provided'); ?></strong>
                        </a>
                        <a class="quick-action" href="<?php echo $mobile !== 'N/A' ? 'tel:' . htmlspecialchars($mobile) : '#'; ?>">
                            <i class="fas fa-phone"></i>
                            <span>Phone</span>
                            <strong><?php echo htmlspecialchars($mobile !== 'N/A' ? $mobile : 'Not Provided'); ?></strong>
                        </a>
                    </div>
                </div>
            </aside>

            <main class="detail-panel">
                <section class="hero-strip">
                    <div>
                        <h1><?php echo htmlspecialchars($cus_name); ?></h1>
                        <p>A premium customer profile view for quick account review, contact lookup, and customer context.</p>
                    </div>
                    <div class="mini-metric">
                        <span>Customer ID</span>
                        <strong><?php echo htmlspecialchars($customer_id); ?></strong>
                    </div>
                </section>

                <section class="bento-grid">
                    <div class="detail-card">
                        <div class="card-title-row">
                            <span class="card-icon"><i class="fas fa-address-card"></i></span>
                            <h5>Contact</h5>
                        </div>
                        <div class="detail-list">
                            <div class="detail-item">
                                <span>Email Address</span>
                                <?php if ($email !== 'N/A'): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a>
                                <?php else: ?>
                                    <strong>Not Provided</strong>
                                <?php endif; ?>
                            </div>
                            <div class="detail-item">
                                <span>Phone Number</span>
                                <?php if ($mobile !== 'N/A'): ?>
                                        <a href="tel:<?php echo htmlspecialchars($mobile); ?>"><?php echo htmlspecialchars($mobile); ?></a>
                                <?php else: ?>
                                    <strong>Not Provided</strong>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="card-title-row">
                            <span class="card-icon"><i class="fas fa-building"></i></span>
                            <h5>Profile</h5>
                        </div>
                        <div class="detail-list">
                            <div class="detail-item">
                                <span>Representative</span>
                                <strong><?php echo htmlspecialchars($rep_name); ?></strong>
                            </div>
                            <div class="detail-item">
                                <span>City</span>
                                <strong><?php echo htmlspecialchars($city); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="card-title-row">
                            <span class="card-icon"><i class="fas fa-map-location-dot"></i></span>
                            <h5>Location</h5>
                        </div>
                        <div class="detail-list">
                            <div class="detail-item">
                                <span>Address</span>
                                <strong><?php echo htmlspecialchars($address); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="card-title-row">
                            <span class="card-icon"><i class="fas fa-shield-halved"></i></span>
                            <h5>Account</h5>
                        </div>
                        <div class="detail-list">
                            <div class="detail-item">
                                <span>Status</span>
                                <strong>Active Customer</strong>
                            </div>
                            <div class="detail-item">
                                <span>Date Added</span>
                                <strong><?php echo htmlspecialchars($date_of_adding); ?></strong>
                            </div>
                            <div class="detail-item">
                                <span>Reference By</span>
                                <strong><?php echo htmlspecialchars($reference_by); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="detail-card wide">
                        <div class="card-title-row">
                            <span class="card-icon"><i class="fas fa-note-sticky"></i></span>
                            <h5>Notes</h5>
                        </div>
                        <div class="note-box">
                            <?php echo $notes !== '' ? nl2br(htmlspecialchars($notes)) : 'No notes recorded for this customer.'; ?>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once('../inc/footer.php'); ?>
</body>
</html>
