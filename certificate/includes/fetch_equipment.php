<?php
$eq_stmt = $conn->prepare("SELECT * FROM operator_equipment WHERE assessment_id=? ORDER BY equipment_number ASC");
$eq_stmt->bind_param("i", $assessment_id);
$eq_stmt->execute();
$equipments_raw = $eq_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$equipment = [];
foreach ($equipments_raw as $eq) {
    $equipment[] = [
        'type'         => trim($eq['equipment_type'] ?? ''),
        'manufacturer' => trim($eq['manufacturer'] ?? ''),
        'model'        => trim($eq['model'] ?? ''),
        'capacity'     => trim($eq['capacity'] ?? '')
    ];
}
return $equipment;
