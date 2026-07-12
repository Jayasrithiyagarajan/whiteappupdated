<?php
require_once('../vendor/autoload.php');
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('<table><tr><td>TEST TABLE</td></tr></table>');
$mpdf->Output('test.pdf', 'F');
echo 'OK';
?>
