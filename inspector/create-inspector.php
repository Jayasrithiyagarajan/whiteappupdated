<?php
include_once('../inc/function.php'); 
include_once('./get-inspector.php');
?>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white">
                <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">Create Inspector</h4>
                    </div>
                    <div class="col-6 text-right">
                        <a href="./all-inspector.php" class="btn btn-primary">View List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="add-inspector.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-element py-30 multiple-column">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                    <label class="font-14 bold mb-2">Inspector ID</label>
                                    <input type="text" name="inspector_id" class="theme-input-style" 
                                    value="<?php echo htmlspecialchars($new_id); ?>" readonly>
                                    
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Inspector Name</label>
                                    <input type="text" class="theme-input-style" name="inspector_name" placeholder="Type Your Name" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Email</label>
                                    <input type="email" class="theme-input-style" name="email" placeholder="Your Email Address" required>
                                </div>
                                <div class="form-group">
    <label class="font-14 bold mb-2">Handle Crane</label>
    <div class="form-control" style="height:auto;">
        <label><input type="checkbox" name="handle_crane[]" value="arc-welding-machine" checked> ARC WELDING MACHINE</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="articulating_boom" checked> ARTICULATING BOOM CRANES</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="base_mounted_drum" checked> BASE MOUNTED DRUM HOIST (WINCHES)</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="bulldozer" checked> BULLDOZER</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="elevators" checked> ELEVATORS AND ESCALATORS</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="excavator" checked> HYDRAULIC EXCAVATOR</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="fixed-cranes-hoist" checked> FIXED CRANES & HOISTS</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="forklift" checked> FORK LIFT</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="frames-and-mobile-gantries" checked> A-FRAMES AND MOBILE GANTRIES</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="jib-davit" checked> JIB CRANES & DAVITS</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="lifting-beam-spreader-bar" checked> LIFTING BEAMS/SPREADER BARS</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="manbaskets" checked> MANBASKET</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="marine-offshore-cranes" checked> MARINE & OFFSHORE CRANES</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="mobile_locomotive" checked> MOBILE & LOCOMOTIVE CRANES</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="motor-grade" checked> MOTOR GRADER</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="powered-platforms" checked> POWERED PLATFORMS (SKY CLIMBERS)</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="side-boom-tractors" checked> SIDE BOOM TRACTORS</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="stbd-crane" checked> CRANE HEALTH CHECK</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="storage-retrieval" checked> STORAGE RETRIEVAL</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="tower-cranes" checked> TOWER CRANES</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="vehicle_mounted_elevating" checked> VEHICLE MOUNTED ELEVATING</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="wheel-loader" checked> WHEEL, COMPACT SKID LOADER, & PIPE LOGGER</label><br>
        <label><input type="checkbox" name="handle_crane[]" value="general-purpose" checked> ALL-PURPOSE EQUIPMENT CHECKLIST</label>
        <label><input type="checkbox" name="handle_crane[]" value="ndt" checked> NDT CHECKLIST</label>
        <label><input type="checkbox" name="handle_crane[]" value="sticker" checked> STICKER CHECKLIST</label>
    </div>
</div>

                                 <div class="form-group">
                                    <label class="font-14 bold mb-2">LEEA Number</label>
                                    <input type="text" class="theme-input-style" name="leea_number" placeholder="LEEA NUMBER" required>
                                </div> 
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Mobile</label>
                                    <input type="text" class="theme-input-style" name="mobile" placeholder="Contact Number" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Password</label>
                                    <input type="password" class="theme-input-style" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Address</label>
                                    <textarea class="form-control" name="address" rows="4" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">City</label>
                                    <select class="form-control" name="city">
                                        <option value="Kobar">Al Kobar</option>
                                        <option value="Riyadh">Riyadh</option>
                                    </select>
                                </div>
                                <div class="form-group pt-4">
                                    <label for="profile_photo" class="font-14 bold mb-2">Upload Photo</label>
                                    <input type="file" class="form-control" name="profile_photo" accept="image/*" required>
                                </div>
                                <div class="form-group pt-4">
                                    <label for="signature_photo" class="font-14 bold mb-2">Upload Signature</label>
                                    <input type="file" class="form-control" name="signature_photo" accept="image/*" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group pt-1">
                            <label class="custom-checkbox position-relative mr-2">
                                <input type="checkbox" name="info_correct" required>
                                <span class="checkmark"></span>
                            </label>
                            <label>Confirm whether the provided details are correct</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary" name="save_inspector">Create Now</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include_once('../inc/footer.php'); ?>

<script>
    document.querySelector("form").addEventListener("submit", function (event) {
        let valid = true;

        document.querySelectorAll("input[required], textarea[required], select[required]").forEach((input) => {
            if (!input.value.trim()) {
                valid = false;
                input.style.border = "2px solid red";
            } else {
                input.style.border = "";
            }
        });

        let checkboxes = document.querySelectorAll("input[type='checkbox'][name='handle_crane[]']");
        let isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        if (!isChecked) {
            alert("Please select at least one crane type.");
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
            alert("Please fill out all required fields!");
        }
    });
</script>