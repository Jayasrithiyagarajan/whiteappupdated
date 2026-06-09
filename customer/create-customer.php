<?php include_once('../inc/function.php'); ?>

<style>
    .create-customer-glass {
        position: relative;
        min-height: calc(100vh - 110px);
        padding: 6px 10px 46px;
        background:
            radial-gradient(circle at 12% 6%, rgba(20, 184, 166, 0.16), transparent 28%),
            radial-gradient(circle at 92% 8%, rgba(37, 99, 235, 0.13), transparent 26%),
            linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
        overflow: hidden;
    }

    .create-customer-glass:before {
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

    .create-customer-glass .container-fluid {
        max-width: 1500px;
    }

    .create-customer-shell {
        border: 1px solid rgba(255, 255, 255, 0.62);
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.48));
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        overflow: hidden;
    }

    .create-customer-shell .card-body {
        padding: 0;
    }

    .create-customer-hero {
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

    .create-customer-title {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .create-customer-title-icon {
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

    .create-customer-title h4 {
        margin-bottom: 7px;
        color: #111827;
        font-size: clamp(24px, 2vw, 34px);
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
    }

    .create-customer-title p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.45;
    }

    .create-customer-glass .btn-outline-primary,
    .create-customer-glass .btn-primary {
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

    .create-customer-glass .btn-outline-primary {
        padding: 11px 18px;
        border: 1px solid rgba(37, 99, 235, 0.24);
        background: rgba(255, 255, 255, 0.62);
        color: #1d4ed8;
    }

    .create-customer-glass .btn-primary {
        min-width: 190px;
        padding: 13px 24px;
        border: 0;
        background: linear-gradient(135deg, #2563eb 0%, #16a3d8 52%, #14b8a6 100%);
        color: #fff;
        box-shadow: 0 18px 34px rgba(37, 99, 235, 0.26);
    }

    .create-customer-glass .btn-outline-primary:hover,
    .create-customer-glass .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 22px 42px rgba(20, 184, 166, 0.2);
    }

    .create-customer-form {
        padding: 28px;
    }

    .create-customer-section {
        min-height: 100%;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.62);
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.48);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .create-customer-section-title {
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

    .create-customer-section-title i {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: rgba(20, 184, 166, 0.14);
        color: #0f766e;
    }

    .create-customer-glass .form-group {
        margin-bottom: 18px;
    }

    .create-customer-glass label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 800;
    }

    .create-customer-glass .theme-input-style {
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

    .create-customer-glass .theme-input-style:focus {
        border-color: rgba(37, 99, 235, 0.42);
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .create-customer-glass .theme-input-style[readonly] {
        background: rgba(241, 245, 249, 0.78);
        color: #475569;
    }
    
    .create-customer-glass select.theme-input-style {
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

    .create-customer-glass input[type="file"].form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px dashed rgba(148, 163, 184, 0.4);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.5);
        color: #111827;
        font-weight: 600;
    }
    
    .create-customer-glass textarea.form-control {
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

    .create-customer-actions {
        margin-top: 30px;
        padding-top: 24px;
        border-top: 1px solid rgba(148, 163, 184, 0.18);
        text-align: center;
    }

    @media (max-width: 991px) {
        .create-customer-form {
            padding: 22px;
        }

        .create-customer-section {
            padding: 20px;
        }
    }

    @media (max-width: 767px) {
        .create-customer-glass {
            padding: 0 0 32px;
        }

        .create-customer-shell {
            border-radius: 18px;
        }

        .create-customer-hero {
            flex-direction: column;
            align-items: stretch;
            padding: 22px;
        }

        .create-customer-title {
            align-items: flex-start;
        }

        .create-customer-title-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
        }

        .create-customer-hero a,
        .create-customer-hero button,
        .create-customer-glass .btn-primary {
            width: 100%;
        }

        .create-customer-form {
            padding: 18px;
        }
    }
</style>

<!-- Main Content -->
<div class="main-content create-customer-glass">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card mb-30 create-customer-shell">
                    <div class="card-body">
                        <div class="create-customer-hero">
                            <div class="create-customer-title">
                                <span class="create-customer-title-icon"><i class="icofont-users-alt-4"></i></span>
                                <div>
                                    <h4 class="font-20">Customer Create</h4>
                                    <p>Add new customer details and contact information.</p>
                                </div>
                            </div>
                            <a href="./customer-list.php">
                                <button type="button" class="btn btn-outline-primary"><i class="icofont-list"></i> View List</button>
                            </a>
                        </div>

                        <form action="add-customer.php" method="POST" enctype="multipart/form-data" class="create-customer-form">
                            <div class="row">
                                <!-- Company Details Section -->
                                <div class="col-lg-6 mb-30">
                                    <div class="create-customer-section">
                                        <h4 class="font-16 create-customer-section-title"><i class="icofont-building"></i> Company Details</h4>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Company Name / Client <span class="text-danger">*</span></label>
                                            <input type="text" class="theme-input-style" name="customer_name" placeholder="Type Company Name" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="theme-input-style" name="email" placeholder="Your Email Address" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Rep.Name</label>
                                            <input type="text" class="theme-input-style" name="rep_name" placeholder="Rep Name">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Upload Profile Photo <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="profile_photo" accept="image/*" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">DATE OF adding <span class="text-danger">*</span></label>
                                            <input type="date" class="theme-input-style" name="date_of_adding" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Reference by</label>
                                            <input type="text" class="theme-input-style" name="reference_by" placeholder="Reference by">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact & Address Section -->
                                <div class="col-lg-6 mb-30">
                                    <div class="create-customer-section">
                                        <h4 class="font-16 create-customer-section-title"><i class="icofont-location-pin"></i> Contact & Address</h4>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Mobile <span class="text-danger">*</span></label>
                                            <input type="text" class="theme-input-style" name="mobile" placeholder="Contact Number" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="theme-input-style" name="password" placeholder="Password" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Address</label>
                                            <textarea class="form-control" name="address" rows="4"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">City</label>
                                            <select class="theme-input-style" name="city">
                                                <option value="Khobar">Khobar</option>
                                                <option value="Dammam">Dammam</option>
                                                <option value="Jubail">Jubail</option>
                                                <option value="Riyadh">Riyadh</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Customer Signature <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="signature_photo" accept="image/*" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-14 bold mb-2">Notes</label>
                                            <textarea class="form-control" name="notes" rows="4" placeholder="Additional Notes"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group pt-1">
                                <label class="custom-checkbox position-relative mr-2">
                                    <input type="checkbox" name="info_correct">
                                    <span class="checkmark"></span>
                                </label>
                                <label for="check5" class="d-inline-block mt-1">Confirm whether the provided details are correct</label>
                            </div>

                            <div class="form-row">
                                <div class="col-12 create-customer-actions">
                                    <button type="submit" class="btn btn-primary long" name="save_all">Save All</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>