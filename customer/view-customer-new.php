<?php 
include_once('../file/config.php');

$cus_name = ""; 
$profilePhoto = ""; 

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
        
        // Generate a random color based on customer ID for initials background
        $colors = ['#6366f1', '#a855f7', '#ec4899', '#f43f5e', '#f97316', '#eab308', '#22c55e', '#14b8a6', '#0ea5e9'];
        $colorIndex = crc32($customer_id) % count($colors);
        $bgColor = $colors[$colorIndex];
        
        $initials = strtoupper(substr(trim($cus_name), 0, 2));
    } else {
        echo "No customer found with ID: " . htmlspecialchars($customer_id);
        exit;
    }
} else {
    echo "Customer ID not provided.";
    exit;
}

include_once('../inc/customer-option.php');
?>
<style>
/* PREMIUM PROFILE UI */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

.premium-ui-wrapper {
    font-family: 'Outfit', sans-serif;
    background-image: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
    min-height: calc(100vh - 80px);
    padding: 2.5rem;
    color: #0f172a;
}

.premium-ui-wrapper * {
    box-sizing: border-box;
}

.premium-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: color 0.2s;
}
.btn-back:hover {
    color: #6366f1;
    text-decoration: none;
}

.profile-layout {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 2rem;
    align-items: start;
}

.glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(149, 157, 165, 0.1);
    padding: 2.5rem;
    border: 1px solid rgba(255, 255, 255, 0.6);
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* User Card */
.avatar-container {
    text-align: center;
    margin-bottom: 1.5rem;
}

.avatar-initials {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: <?php echo $bgColor; ?>;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: 700;
    margin: 0 auto;
    border: 4px solid white;
    box-shadow: 0 8px 24px rgba(99, 102, 241, 0.25);
    letter-spacing: 1px;
}

.customer-name {
    font-size: 1.8rem;
    font-weight: 700;
    color: #0f172a;
    margin: 1rem 0 0.5rem 0;
    text-align: center;
}

.customer-id {
    font-size: 0.95rem;
    color: #64748b;
    background: #f1f5f9;
    padding: 6px 16px;
    border-radius: 20px;
    display: inline-block;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.quick-contact {
    margin-top: 2rem;
    border-top: 1px solid rgba(226, 232, 240, 0.8);
    padding-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.contact-row {
    display: flex;
    align-items: center;
    gap: 14px;
    color: #475569;
    font-size: 1rem;
    font-weight: 500;
}
.contact-row i {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e0e7ff;
    color: #6366f1;
    border-radius: 10px;
    font-size: 1.1rem;
}

/* Detail Card */
.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 2rem 0;
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(226, 232, 240, 0.8);
    display: flex;
    align-items: center;
    gap: 12px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 2rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #f8fafc;
    padding: 1.25rem;
    border-radius: 12px;
    border: 1px solid rgba(226, 232, 240, 0.6);
    transition: transform 0.2s, box-shadow 0.2s;
}

.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.info-label {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-value {
    font-size: 1.05rem;
    color: #0f172a;
    font-weight: 500;
    word-break: break-word;
}

.notes-section {
    margin-top: 2rem;
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    border-radius: 8px;
    padding: 1.5rem;
    font-size: 1rem;
    line-height: 1.6;
    color: #78350f;
}

.notes-title {
    font-weight: 700;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.actions-row {
    margin-top: 2rem;
    display: flex;
    gap: 15px;
    border-top: 1px solid rgba(226, 232, 240, 0.8);
    padding-top: 2rem;
}

.btn-primary-custom {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
}
.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    color: white;
    text-decoration: none;
}

.btn-secondary-custom {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: white;
    color: #475569;
    padding: 12px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: 1px solid #cbd5e1;
    cursor: pointer;
}
.btn-secondary-custom:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #94a3b8;
    transform: translateY(-2px);
    text-decoration: none;
}

@media (max-width: 992px) {
    .profile-layout {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="main-content">
    <div class="premium-ui-wrapper">
        <div class="premium-container">
            
            <div class="page-header">
                <a href="customer-list.php" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Back to Customers
                </a>
            </div>

            <div class="profile-layout">
                
                <!-- Left Sidebar: Basic Info -->
                <div class="glass-card">
                    <div class="avatar-container">
                        <div class="avatar-initials">
                            <?php echo $initials; ?>
                        </div>
                        <h1 class="customer-name"><?php echo htmlspecialchars($cus_name); ?></h1>
                        <div class="customer-id">
                            <i class="fa-solid fa-hashtag"></i> <?php echo htmlspecialchars($row['cus_id']); ?>
                        </div>
                    </div>

                    <div class="quick-contact">
                        <?php if (!empty($row['email'])): ?>
                        <div class="contact-row">
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" style="color: inherit; text-decoration: none;">
                                <?php echo htmlspecialchars($row['email']); ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($row['mobile'])): ?>
                        <div class="contact-row">
                            <i class="fa-solid fa-phone"></i>
                            <a href="tel:<?php echo htmlspecialchars($row['mobile']); ?>" style="color: inherit; text-decoration: none;">
                                <?php echo htmlspecialchars($row['mobile']); ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($row['city'])): ?>
                        <div class="contact-row">
                            <i class="fa-solid fa-location-dot"></i>
                            <span><?php echo htmlspecialchars($row['city']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right Sidebar: Details -->
                <div class="glass-card">
                    <h3 class="section-title">
                        <i class="fa-solid fa-address-card" style="color: #6366f1;"></i> Customer Details
                    </h3>

                    <div class="detail-grid">
                        <div class="info-item">
                            <div class="info-label">Full Address</div>
                            <div class="info-value"><?php echo !empty($row['address']) ? nl2br(htmlspecialchars($row['address'])) : 'N/A'; ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Date Added</div>
                            <div class="info-value">
                                <?php 
                                    if(!empty($row['date_of_adding'])) {
                                        echo date("F j, Y", strtotime($row['date_of_adding']));
                                    } else {
                                        echo 'N/A';
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Sales Representative</div>
                            <div class="info-value"><?php echo !empty($row['rep_name']) ? htmlspecialchars($row['rep_name']) : 'N/A'; ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Referred By</div>
                            <div class="info-value"><?php echo !empty($row['reference_by']) ? htmlspecialchars($row['reference_by']) : 'N/A'; ?></div>
                        </div>
                    </div>

                    <?php if (!empty($row['notes'])): ?>
                    <div class="notes-section">
                        <div class="notes-title">
                            <i class="fa-regular fa-clipboard"></i> Notes
                        </div>
                        <?php echo nl2br(htmlspecialchars($row['notes'])); ?>
                    </div>
                    <?php endif; ?>

                    <div class="actions-row">
                        <a href="edit-customer.php?cusid=<?php echo urlencode($customer_id); ?>" class="btn-primary-custom">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                        </a>
                        <a href="project.php?cus_id=<?php echo urlencode($customer_id); ?>" class="btn-secondary-custom">
                            <i class="fa-solid fa-diagram-project"></i> View Projects
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>
