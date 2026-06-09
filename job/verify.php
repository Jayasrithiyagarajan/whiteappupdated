<?php
include_once('../file/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Sticker/Project Number</title>
    
    <!-- Load jQuery First -->
    <script src="<?php echo $url2; ?>assets/js/jquery.min.js"></script>
    <script>
        // Fallback to jQuery CDN if local file is not found
        window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>');
    </script>

    <!-- Load Bootstrap -->
    <script src="<?php echo $url2; ?>assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo $url2; ?>assets/css/style.css">
    
    <style>
        #errorMessage {
            color: #ff0000;
            font-size: 14px;
            margin-top: 10px;
            display: none;
            background: rgba(255, 0, 0, 0.1);
            padding: 8px;
            border-radius: 4px;
        }

        .modal {
            display: block;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            position: relative;
            width: 80%;
            max-width: 500px;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }
        input {
            padding: 10px;
            width: 80%;
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .tab-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        .tab-button {
            background-color: #ddd;
            color: #333;
        }
        .tab-button.active {
            background-color: #007bff;
            color: white;
        }
        .input-container {
            display: none;
        }
        .input-container.active {
            display: block;
        }
        .small-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Popup Modal -->
    <div id="qrPopup" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Enter your verification number</h3>
            
            <div class="tab-buttons">
                <button class="tab-button active" data-tab="sticker">Sticker No</button>
                <button class="tab-button" data-tab="project">Report No</button>
            </div>
            
            <div class="input-container active" id="stickerInput">
                <input type="text" id="stickerNo" placeholder="Sticker No">
            </div>
            
            <div class="input-container" id="projectInput">
                <input type="text" id="projectNo" placeholder="Report No (NDT Equipment only)">
                <p class="small-text">Note: Only NDT Equipment projects can be accessed with report number</p>
            </div>
            
            <p id="errorMessage"></p>
            <button id="submitVerification">Submit</button>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            var modal = $("#qrPopup");  
            var closeBtn = $(".close"); 
            var errorMessage = $("#errorMessage");
            var currentTab = "sticker"; // Default tab

            // Tab switching
            $(".tab-button").click(function() {
                $(".tab-button").removeClass("active");
                $(this).addClass("active");
                currentTab = $(this).data("tab");
                
                $(".input-container").removeClass("active");
                $("#" + currentTab + "Input").addClass("active");
                
                // Clear previous inputs and errors
                $("#stickerNo, #projectNo").val("");
                errorMessage.hide();
            });

            closeBtn.click(function() {
                modal.hide();
                window.location.href = "job-details.php"; 
            });

            $(window).click(function(event) {
                if ($(event.target).is("#qrPopup")) {
                    modal.hide();
                    window.location.href = "job-details.php"; 
                }
            });

            $("#submitVerification").click(function() {
                errorMessage.hide();
                
                var identifier, verificationType;
                
                if (currentTab === "sticker") {
                    identifier = $("#stickerNo").val().trim();
                    verificationType = "sticker";
                } else {
                    identifier = $("#projectNo").val().trim();
                    verificationType = "project";
                }

                if (!identifier) {
                    errorMessage.text("Please enter a " + (currentTab === "sticker" ? "sticker number" : "project number"));
                    errorMessage.show();
                    return;
                }

                $.ajax({
                    url: "verify_identifier.php",
                    type: "POST",
                    data: { 
                        identifier: identifier,
                        type: verificationType
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.valid) {
                            if (verificationType === "sticker") {
                                window.location.href = "form.php?stickerNo=" + encodeURIComponent(identifier);
                            } else {
                                window.location.href = "form.php?projectNo=" + encodeURIComponent(identifier);
                            }
                        } else {
                            errorMessage.text(response.message || "Verification failed. Please check your number and try again.");
                            errorMessage.show();
                            
                            // If project number was rejected, switch to sticker tab
                            if (verificationType === "project" && response.message.includes("Please use sticker number")) {
                                $(".tab-button").removeClass("active");
                                $(".tab-button[data-tab='sticker']").addClass("active");
                                currentTab = "sticker";
                                $(".input-container").removeClass("active");
                                $("#stickerInput").addClass("active");
                                $("#stickerNo").focus();
                            }
                        }
                    },
                    error: function() {
                        errorMessage.text("Error during verification. Please try again.");
                        errorMessage.show();
                    }
                });
            });
        });
    </script>
</body>
</html>