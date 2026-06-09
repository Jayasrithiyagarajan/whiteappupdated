<?php
include_once('../inc/function.php'); 
include_once('../file/config.php');

$auto_user_id = 'EMP001'; // Default
$result = mysqli_query($conn, "SELECT user_id FROM new_users WHERE user_id LIKE 'EMP%' ORDER BY user_id DESC LIMIT 1");
if ($row = mysqli_fetch_assoc($result)) {
    $last_id = $row['user_id']; // e.g., "EMP007"
    $num = (int)substr($last_id, 3); // get 007 as number
    $next_num = str_pad($num + 1, 3, '0', STR_PAD_LEFT); // 008
    $auto_user_id = 'EMP' . $next_num; // EMP008
}
//  include_once('./get-user.php');
?>

<style>
    .create-user-glass {
        position: relative;
        min-height: calc(100vh - 110px);
        padding: 6px 10px 46px;
        background:
            radial-gradient(circle at 12% 6%, rgba(20, 184, 166, 0.16), transparent 28%),
            radial-gradient(circle at 92% 8%, rgba(37, 99, 235, 0.13), transparent 26%),
            linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
        overflow: hidden;
    }

    .create-user-glass:before {
        content: "";
        position: fixed;
        right: 6%;
        top: 140px;
        width: 340px;
        height: 340px;
        border-radius: 999px;
        background: rgba(20, 184, 166, 0.1);
        filter: blur(4px);
        pointer-events: none;
        z-index: -1;
    }

    .create-user-glass .container-fluid {
        max-width: 1500px;
    }

    .create-user-shell {
        border: 1px solid rgba(255, 255, 255, 0.62);
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.48));
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        overflow: hidden;
    }

    .create-user-shell .card-body {
        padding: 0;
    }

    .create-user-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 28px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        background:
            radial-gradient(circle at top right, rgba(37, 99, 235, 0.1), transparent 36%),
            linear-gradient(135deg, rgba(255, 255, 255, 0.72), rgba(255, 255, 255, 0.36));
    }

    .create-user-title {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .create-user-title-icon {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.16), rgba(20, 184, 166, 0.14));
        color: #2563eb;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 16px 32px rgba(15, 23, 42, 0.1);
        font-size: 24px;
        flex: 0 0 auto;
    }

    .create-user-title h4 {
        margin-bottom: 7px;
        color: #111827;
        font-size: clamp(24px, 2vw, 34px);
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
    }

    .create-user-title p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.45;
    }

    .create-user-glass .btn-outline-primary,
    .create-user-glass .btn-primary {
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 12px;
        font-weight: 800;
        box-shadow: 0 16px 32px rgba(37, 99, 235, 0.14);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .create-user-glass .btn-outline-primary {
        padding: 11px 18px;
        border: 1px solid rgba(37, 99, 235, 0.24);
        background: rgba(255, 255, 255, 0.62);
        color: #1d4ed8;
    }

    .create-user-glass .btn-primary {
        min-width: 190px;
        padding: 13px 24px;
        border: 0;
        background: linear-gradient(135deg, #2563eb 0%, #16a3d8 52%, #14b8a6 100%);
        color: #fff;
        box-shadow: 0 18px 34px rgba(37, 99, 235, 0.26);
    }

    .create-user-glass .btn-outline-primary:hover,
    .create-user-glass .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 22px 42px rgba(20, 184, 166, 0.2);
    }

    .create-user-form {
        padding: 28px;
    }

    .create-user-section {
        min-height: 100%;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.62);
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.48);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .create-user-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
        padding-bottom: 14px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        color: #111827;
        font-size: 17px;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
    }

    .create-user-section-title i {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: rgba(20, 184, 166, 0.14);
        color: #0f766e;
    }

    .create-user-glass .form-group {
        margin-bottom: 18px;
    }

    .create-user-glass label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 800;
    }

    .create-user-glass .theme-input-style {
        width: 100%;
        min-height: 48px;
        padding: 12px 14px;
        border: 1px solid rgba(148, 163, 184, 0.26);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.72);
        color: #111827;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88);
        font-weight: 700;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .create-user-glass .theme-input-style:focus {
        border-color: rgba(37, 99, 235, 0.42);
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .create-user-glass .theme-input-style[readonly] {
        background: rgba(241, 245, 249, 0.78);
        color: #475569;
    }
    
    .create-user-glass select.theme-input-style {
        width: 100%;
        min-height: 48px;
        padding: 12px 14px;
        border: 1px solid rgba(148, 163, 184, 0.26);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.72);
        color: #111827;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88);
        font-weight: 700;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .create-user-glass input[type="file"].form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px dashed rgba(148, 163, 184, 0.4);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.5);
        color: #111827;
        font-weight: 600;
    }
    
    .create-user-glass textarea.form-control {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid rgba(148, 163, 184, 0.26);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.72);
        color: #111827;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88);
        font-weight: 700;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        resize: vertical;
    }

    .create-user-actions {
        margin-top: 30px;
        padding-top: 24px;
        border-top: 1px solid rgba(148, 163, 184, 0.18);
        text-align: center;
    }

    @media (max-width: 991px) {
        .create-user-form {
            padding: 22px;
        }

        .create-user-section {
            padding: 20px;
        }
    }

    @media (max-width: 767px) {
        .create-user-glass {
            padding: 0 0 32px;
        }

        .create-user-shell {
            border-radius: 18px;
        }

        .create-user-hero {
            flex-direction: column;
            align-items: stretch;
            padding: 22px;
        }

        .create-user-title {
            align-items: flex-start;
        }

        .create-user-title-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
        }

        .create-user-hero a,
        .create-user-hero button,
        .create-user-glass .btn-primary {
            width: 100%;
        }

        .create-user-form {
            padding: 18px;
        }
    }
</style>

<!-- Main Content -->
<div class="main-content create-user-glass">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card mb-30 create-user-shell">
                    <div class="card-body">
                        <div class="create-user-hero">
                            <div class="create-user-title">
                                <span class="create-user-title-icon"><i class="icofont-business-man"></i></span>
                                <div>
                                    <h4 class="font-20">Create User</h4>
                                    <p>Add a new user and assign roles and permissions.</p>
                                </div>
                            </div>
                            <a href="./all-user.php">
                                <button type="button" class="btn btn-outline-primary"><i class="icofont-list"></i> View List</button>
                            </a>
                        </div>

                        <form action="add-user.php" method="POST" enctype="multipart/form-data" class="create-user-form">
                            <div class="row">
                                <!-- Personal Info Section -->
                                <div class="col-lg-6 mb-30">
                                    <div class="create-user-section">
                                        <h4 class="font-16 create-user-section-title"><i class="icofont-id-card"></i> Personal Info</h4>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">User ID <span class="text-danger">*</span></label>
                                            <input type="text" class="theme-input-style" name="user_id" value="<?= $auto_user_id ?>" readonly required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">User Name <span class="text-danger">*</span></label>
                                            <input type="text" class="theme-input-style" name="username" placeholder="Type Your Name" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="theme-input-style" name="email" placeholder="Your Email Address" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Emp ID <span class="text-danger">*</span></label>
                                            <input type="text" class="theme-input-style" name="emp_id" placeholder="Employee ID" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Mobile <span class="text-danger">*</span></label>
                                            <input type="text" class="theme-input-style" name="mobile" placeholder="Contact Number" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Account & Settings Section -->
                                <div class="col-lg-6 mb-30">
                                    <div class="create-user-section">
                                        <h4 class="font-16 create-user-section-title"><i class="icofont-ui-settings"></i> Account & Settings</h4>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="theme-input-style" name="password" placeholder="Password" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="address" rows="4" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">City</label>
                                            <select class="theme-input-style" name="city">
                                                <option value="Kobar">Al Kobar</option>
                                                <option value="Riyadh">Riyadh</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Select Role <span class="text-danger">*</span></label>
                                            <select class="theme-input-style" name="role" required>
                                                <option value="">Select Role</option>
                                                <option value="admin">Admin</option>
                                                <option value="reviewer">Reviewer</option>
                                                <option value="quality controller">Quality Controller</option>
                                                <option value="document controller">Document Controller</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="profile_photo" class="font-14 bold mb-2">Upload Profile Photo <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="profile_photo" accept="image/*" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group pt-1">
                                <label class="custom-checkbox position-relative mr-2">
                                    <input type="checkbox" name="info_correct" required>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="d-inline-block mt-1">Confirm whether the provided details are correct</label>
                            </div>

                            <div class="form-row">
                                <div class="col-12 create-user-actions">
                                    <button type="submit" class="btn btn-primary long" name="save_user">Create Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const submitButton = document.querySelector("button[type='submit']");

    form.addEventListener("submit", function () {
        // Disable the submit button to prevent double submission
        submitButton.disabled = true;
        submitButton.innerText = "Creating User..."; // Optional: Change button text
    });
});
</script>

<?php include_once('../inc/footer.php'); ?>