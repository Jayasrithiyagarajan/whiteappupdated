<?php
$base_url = 'http://localhost/whiteappupdated/';

$inspector_name = $assessment['inspector_name'] ?? '';
$folder_name = strtolower(str_replace(' ', '_', $inspector_name));
$local_sig_path = __DIR__ . '/../../inspector/uploads/' . $folder_name . '/images/signature_image.jpg';

$assessor_sig_path = '';
if (!empty($inspector_name) && file_exists($local_sig_path)) {
    $assessor_sig_path = $base_url . 'inspector/uploads/' . urlencode($folder_name) . '/images/signature_image.jpg';
}

return [
    'assessor' => [
        'name'        => ucwords(strtolower($assessment['inspector_name'] ?? '')),
        'designation' => 'ASSESSOR / INSPECTOR',
        'signature'   => $assessor_sig_path
    ],
    'manager' => [
        'name'        => 'Eng. Khalid A. Alghamdi',
        'designation' => 'OPERATIONS MANAGER',
        'signature'   => $base_url . 'document/uploads/Khaled%20A.%20Alghamdi.jpg'
    ]
];
