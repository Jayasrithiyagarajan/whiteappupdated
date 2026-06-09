<?php
// Include necessary files
include_once('../file/config.php');
include_once('../inc/function.php');

// Check if the user is logged in
$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Internal Styles for Pagination -->
    <style>
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #007bff;
            border-radius: 3px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<!-- Main Content -->
<div class="main-content">
   <div class="container-fluid">
      <div class="row">

<div class="col-xl-12">
    <!-- Card -->
    <div class="card mb-30">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Latest News</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newsModal">
                    Update News
                </button>
            </div>
            <div id="newsContent">
                <?php
                // Fetch the latest news from the database
                $news_query = mysqli_query($conn, "SELECT * FROM latest_news ORDER BY created_at DESC LIMIT 1");
                if ($news_query && mysqli_num_rows($news_query) > 0) {
                    $news = mysqli_fetch_assoc($news_query);
                    echo nl2br(htmlspecialchars($news['news']));
                } else {
                    echo "No news available.";
                }
                ?>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
</div>
</div>
</div>

<!-- News Modal -->
<div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newsModalLabel">Update News</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newsForm">
                    <div class="form-group">
                        <label for="newsText">News</label>
                        <textarea class="form-control" id="newsText" name="news" rows="5" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNews">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#saveNews').click(function() {
        var newsText = $('#newsText').val();
        if (newsText.trim() === "") {
            alert("Please enter some news.");
            return;
        }

        console.log("Sending news:", newsText); // Debug: Log the news text

        $.ajax({
            url: 'save_news.php',
            type: 'POST',
            data: { news: newsText },
            success: function(response) {
                console.log("Response from server:", response); // Debug: Log the server response
                if (response === 'success') {
                    $('#newsModal').modal('hide');
                    $('#newsContent').html(nl2br(newsText)); // Update the news content on the page
                } else {
                    alert('Failed to save news. Server response: ' + response);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error); // Debug: Log any AJAX errors
                alert('An error occurred while saving the news. Please check the console for details.');
            }
        });
    });
});
</script>