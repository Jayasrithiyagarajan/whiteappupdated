<?php
include_once('../inc/function.php');
include_once('../file/config.php');

$sql = "SELECT * FROM operator_cards ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Operator Card List</title>
    <style>
        .list-container {
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }
        .list-header h2 {
            margin: 0;
            color: #333;
        }
        .btn-create {
            background: #4f46e5;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
        }
        .operator-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            vertical-align: middle;
            margin-right: 10px;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #4f46e5;
            font-weight: 600;
        }
        .actions a.delete {
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container-fluid">
            <div class="list-container">
                <div class="list-header">
                    <h2>Operator Cards</h2>
                    <a href="add-card.php" class="btn-create">+ Create New Card</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Operator</th>
                            <th>Certificate No</th>
                            <th>ID / Iqama</th>
                            <th>Expiry Date</th>
                            <th>Examiner</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <img src="../<?= htmlspecialchars($row['photo_path']); ?>" class="operator-img">
                                        <?= htmlspecialchars($row['operator_name']); ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['certificate_no']); ?></td>
                                    <td><?= htmlspecialchars($row['id_iqama']); ?></td>
                                    <td><?= date('d M Y', strtotime($row['expiry_date'])); ?></td>
                                    <td><?= htmlspecialchars($row['examiner_name']); ?></td>
                                    <td class="actions">
                                        <a href="view-card.php?id=<?= $row['id']; ?>" target="_blank">View/Print</a>
                                        <!-- <a href="edit-card.php?id=<?= $row['id']; ?>">Edit</a> -->
                                        <!-- <a href="delete-card.php?id=<?= $row['id']; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a> -->
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No cards found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include_once('../inc/footer.php'); ?>
</body>
</html>








