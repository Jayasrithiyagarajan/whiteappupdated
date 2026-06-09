<?php
include_once('file/config.php');
$res = $conn->query('SHOW COLUMNS FROM checklist_information');
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
?>
