<?php
include 'file/config.php';
$q = mysqli_query($conn, 'DESCRIBE lmi_certificates');
while($r = mysqli_fetch_assoc($q)) {
    print_r($r);
}
?>
