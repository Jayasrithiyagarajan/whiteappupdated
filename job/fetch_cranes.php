<?php
include_once('../file/config.php');

// Crane slug => readable name map
$craneMap = [
    'arc-welding-machine'        => 'ARC WELDING MACHINE',
    'articulating_boom'          => 'ARTICULATING BOOM CRANES',
    'base_mounted_drum'          => 'BASE MOUNTED DRUM HOIST (WINCHES)',
    'bulldozer'                  => 'BULLDOZER',
    'elevators'                  => 'ELEVATORS AND ESCALATORS',
    'excavator'                  => 'HYDRAULIC EXCAVATOR',
    'fixed-cranes-hoist'         => 'FIXED CRANES & HOISTS',
    'forklift'                   => 'FORK LIFT',
    'frames-and-mobile-gantries' => 'A-FRAMES AND MOBILE GANTRIES',
    'jib-davit'                  => 'JIB CRANES & DAVITS',
    'lifting-beam-spreader-bar'  => 'LIFTING BEAMS/SPREADER BARS',
    'manbaskets'                 => 'MANBASKET',
    'marine-offshore-cranes'     => 'MARINE & OFFSHORE CRANES',
    'mobile_locomotive'          => 'MOBILE & LOCOMOTIVE CRANES',
    'motor-grade'                => 'MOTOR GRADER',
    'powered-platforms'          => 'POWERED PLATFORMS / SKY CLIMBERS',
    'side-boom-tractors'         => 'SIDE BOOM TRACTORS',
    'stbd-crane'                 => 'CRANE HEALTH CHECK',
    'storage-retrieval'          => 'STORAGE RETRIEVAL',
    'tower-cranes'               => 'TOWER CRANES',
    'vehicle_mounted_elevating'  => 'VEHICLE MOUNTED ELEVATING',
    'wheel-loader'               => 'WHEEL, COMPACT SKID LOADER, & PIPE LOGGER',
    'general-purpose'            => 'ALL-PURPOSE EQUIPMENT CHECKLIST',
    'ndt'                        => 'NDT CHECKLIST',
    'sticker'                    => 'STICKER CHECKLIST'
];

if (isset($_GET['inspector_name'])) {
    $inspectorName = $_GET['inspector_name'];

    $query = "SELECT handle_crane FROM inspectors WHERE inspector_name = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $inspectorName);
        $stmt->execute();
        $result = $stmt->get_result();

        $options = [];
        if ($row = $result->fetch_assoc()) {
            if (!empty($row['handle_crane'])) {
                $cranes = unserialize($row['handle_crane']);

                if (is_array($cranes)) {
                    foreach ($cranes as $slug) {
                        $cleanSlug = trim($slug);
                        $label = $craneMap[$cleanSlug] ?? "Unknown ($cleanSlug)";
                        $options[] = [
                            'value' => $cleanSlug,
                            'label' => $label
                        ];
                    }
                } else {
                    $options[] = [
                        'value' => '',
                        'label' => 'Invalid crane data format'
                    ];
                }
            } else {
                $options[] = [
                    'value' => '',
                    'label' => 'No cranes available for this inspector'
                ];
            }
        } else {
            $options[] = [
                'value' => '',
                'label' => 'Inspector not found'
            ];
        }

        echo json_encode($options);
    } else {
        echo json_encode([
            ['value' => '', 'label' => 'Database query error']
        ]);
    }
} else {
    echo json_encode([
        ['value' => '', 'label' => 'Inspector name not provided']
    ]);
}
?>