<?php
try {
    $mpdf = new \Mpdf\Mpdf([
        'format' => 'A4',
        'margin_left' => 0, 'margin_right' => 0,
        'margin_top' => 0, 'margin_bottom' => 0,
        'margin_header' => 0, 'margin_footer' => 0,
        'img_dpi' => 96,
        'tempDir' => __DIR__.'/../../tmp',
        'default_font' => 'dejavusanscondensed',
    ]);
    
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->setAutoPageBreak(false);

    $stylesheet = file_get_contents(__DIR__.'/../assets/css/certificate.css');
    
    // Inject stylesheet into the HTML head
    $html = str_replace('</head>', "<style>\n$stylesheet\n</style>\n</head>", $html);
    
    $mpdf->WriteHTML($html);
    
    if (ob_get_length()) ob_end_clean();
    $mpdf->Output('Operator_Certificate_'.$certificate['certificate_no'].'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
} catch (\Throwable $e) {
    if (ob_get_length()) ob_end_clean();
    http_response_code(500);
    $logMsg = date('Y-m-d H:i:s').' | '.$e->getMessage().' | File:'.$e->getFile().' Line:'.$e->getLine()."\n";
    @file_put_contents(__DIR__.'/../../tmp/cert_error.log', $logMsg, FILE_APPEND);
    echo '<pre style="color:red;font-size:14px;padding:20px;">';
    echo '<b>Certificate Error:</b> '.htmlspecialchars($e->getMessage())."<br><br>";
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
}
