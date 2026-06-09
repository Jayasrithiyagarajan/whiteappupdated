<?php
include_once(__DIR__ . '/_bootstrap.php');

$sections = [
    [
        'title' => 'GENERAL REQUIREMENTS',
        'items' => [
            '1.0' => ['criteria' => 'Rated load and configuration are clearly marked on the equipment', 'reference' => 'ASME B30.16 Sec.1.1.3'],
            '1.1' => ['criteria' => 'Equipment is operated by qualified personnel only', 'reference' => 'ASME B30.16 Sec.1.1.1'],
            '1.2' => ['criteria' => 'Equipment is used within the scope of its design and intended use', 'reference' => 'ASME B30.16 Sec.1.1.2'],
            '1.3' => ['criteria' => 'Equipment is inspected for damage or deficiencies prior to use', 'reference' => 'ASME B30.16 Sec.1.1.4'],
            '1.4' => ['criteria' => 'Equipment is not loaded beyond rated capacity', 'reference' => 'ASME B30.16 Sec.1.1.5'],
            '1.5' => ['criteria' => 'Equipment is positioned or secured to prevent accidental movement', 'reference' => 'ASME B30.16 Sec.1.1.6'],
            '1.6' => ['criteria' => 'Equipment is not altered or modified without manufacturer approval', 'reference' => 'ASME B30.16 Sec.1.1.7'],
            '1.7' => ['criteria' => 'Equipment is properly maintained and lubricated', 'reference' => 'ASME B30.16 Sec.1.1.8'],
            '1.8' => ['criteria' => 'Guards and protective devices are in place and functional', 'reference' => 'ASME B30.16 Sec.1.1.9'],
            '1.9' => ['criteria' => 'Warning devices and labels are present and legible', 'reference' => 'ASME B30.16 Sec.1.1.10'],
            '1.10' => ['criteria' => 'Equipment is inspected at regular intervals', 'reference' => 'ASME B30.16 Sec.1.1.11'],
            '1.11' => ['criteria' => 'Records of inspections and maintenance are maintained', 'reference' => 'ASME B30.16 Sec.1.1.12'],
            '1.12' => ['criteria' => 'Equipment is removed from service when deficiencies are found', 'reference' => 'ASME B30.16 Sec.1.1.13'],
            '1.13' => ['criteria' => 'Repairs are performed by qualified personnel', 'reference' => 'ASME B30.16 Sec.1.1.14']
        ]
    ],
    [
        'title' => 'CRANE RUNWAY AND MONORAIL TRACKS',
        'items' => [
            '2.0' => ['criteria' => 'Runway tracks are properly installed and aligned', 'reference' => 'ASME B30.17 Sec.1.3.1'],
            '2.1' => ['criteria' => 'Runway tracks are securely fastened to supporting structure', 'reference' => 'ASME B30.17 Sec.1.3.2'],
            '2.2' => ['criteria' => 'Runway tracks are free of defects and corrosion', 'reference' => 'ASME B30.17 Sec.1.3.4'],
            '2.3' => ['criteria' => 'Runway tracks have proper end stops installed', 'reference' => 'ASME B30.17 Sec.1.4.2'],
            '2.4' => ['criteria' => 'Runway tracks are properly lubricated', 'reference' => 'ASME B30.17 Sec.1.3.3'],
            '2.5' => ['criteria' => 'Runway tracks support structure is adequate', 'reference' => 'ASME B30.17 Sec.1.3.1(b)']
        ]
    ],
    [
        'title' => 'GUARDS FOR MOVING PARTS',
        'items' => [
            '3.0' => ['criteria' => 'Guards protect moving parts such as gears, chains, chain sprockets', 'reference' => 'ASME B30.17 Sec.1.11.1'],
            '3.1' => ['criteria' => 'Guards protect ropes where liable to come in contact with conductors', 'reference' => 'ASME B30.17 Sec.1.11.2(a)'],
            '3.2' => ['criteria' => 'Guards are provided to prevent contact between crane bridge or runway conductors and hoisting ropes', 'reference' => 'ASME B30.17 Sec.1.11.2(b)']
        ]
    ],
    [
        'title' => 'HOISTING BRAKES',
        'items' => [
            '4.0' => ['criteria' => 'Braking system will stop and hold the load hook when controls are released under any load condition', 'reference' => 'ASME B30.16 Sec.1.2.11(b1-b)'],
            '4.1' => ['criteria' => 'An electric hoist stops and holds the load block in the event of power failure', 'reference' => 'ASME B30.16 Sec.1.2.11(b1-c)'],
            '4.2' => ['criteria' => 'An air hoist stops and holds the load block in the event of air pressure loose', 'reference' => 'ASME B30.16 Sec.1.2.11(c1-b)'],
            '4.3' => ['criteria' => 'Braking systems has means for adjustment to compensate for wear', 'reference' => 'ASME B30.16 Sec.1.2.11(b3/c)'],
            '4.4' => ['criteria' => 'Hand chain operated hoist automatically stops and holds lifted load when the actuating force is removed', 'reference' => 'ASME B30.16 Sec.1.2.11a'],
            '4.5' => ['criteria' => 'Brake adjustment is within manufacturer specifications', 'reference' => 'ASME B30.16 Sec.1.2.11']
        ]
    ],
    [
        'title' => 'ELECTRICAL EQUIPMENT',
        'items' => [
            '5.0' => ['criteria' => 'Control circuit voltage does not exceed 600v for AC or DC', 'reference' => 'ASME B30.17 Sec.1.14.1(b)'],
            '5.1' => ['criteria' => 'Push button enclosure is grounded', 'reference' => 'ASME B30.17 Sec.1.14.1(e)'],
            '5.2' => ['criteria' => 'Push button enclosure is marked for identification of function', 'reference' => 'ASME B30.17 Sec.1.14.1(e)'],
            '5.3' => ['criteria' => 'Parts of electrical equipment are enclosed and are not exposed to inadvertent contact under normal operating conditions', 'reference' => 'ASME B30.17 Sec.1.14.2(a)'],
            '5.4' => ['criteria' => 'Live parts of electrical equipment are protected from direct exposure to grease and oil and protected from dirt and moisture', 'reference' => 'ASME B30.17 Sec.1.14.2(b)'],
            '5.5' => ['criteria' => 'Guards on live parts are not deformed or/and in contact', 'reference' => 'ASME B30.17 Sec.1.9.2(c)'],
            '5.6' => ['criteria' => 'Floor operated cranes controllers return to off position when released', 'reference' => 'ASME B30.17 Sec.1.9.3(b1)'],
            '5.7' => ['criteria' => 'Pendant push buttons that control motion return to off position when pressure is released', 'reference' => 'ASME B30.17 Sec.1.9.3(b2)'],
            '5.8' => ['criteria' => 'Warning signs/labels are provided on the hoist units and electrical enclosures', 'reference' => 'ASME B30.16 Sec.1.1.4'],
            '5.9' => ['criteria' => 'Electrical connections are secure and free of corrosion', 'reference' => 'ASME B30.17 Sec.1.14.1']
        ]
    ],
    [
        'title' => 'LOAD CHAIN, ROPE AND HOOK BLOCK',
        'items' => [
            '6.0' => ['criteria' => 'Chain passes over all load sprockets without binding', 'reference' => 'ASME B30.16 Sec.1.2.8'],
            '6.1' => ['criteria' => 'Hand Operated Chain: Chain length for extension (stretch) tolerance is no longer than 2.5% of unused chain or as per manufacturer recommendations', 'reference' => 'ASME B30.16 Sec.2.5.2(a)'],
            '6.2' => ['criteria' => 'Power Operated Chain: Chain length for extension (stretch) tolerance is no longer than 1.5% of unused chain or as per manufacturer recommendations', 'reference' => 'ASME B30.16 Sec.2.5.2(a)'],
            '6.3' => ['criteria' => 'The chain does not suffer from gouges, nicks, corrosion, weld spatter or distorted links (Judgement to be used as to the suitability or otherwise of using chain with these deficiencies)', 'reference' => 'ASME B30.16 Sec.2.5.2(b)'],
            '6.4' => ['criteria' => 'The chain does not bind, jump or gets noisy when hoist is operated', 'reference' => 'ASME B30.16 Sec.2.6.1(b)'],
            '6.5' => ['criteria' => 'The chain is not stretched or elongated more than 1/4" (6.3 mm) in 12" (305 mm) with reference to the manufacturer\'s manual (roller chain)', 'reference' => 'ASME B30.16 Sec.2.6.1(c1)'],
            '6.6' => ['criteria' => 'The chain is not twisted more than 15 degree in 5 ft. (1.52 m) sections (roller chain)', 'reference' => 'ASME B30.16 Sec.2.6.1(c2)'],
            '6.7' => ['criteria' => 'The roller chain pins, links and rollers move freely and are not corroded, pitted, discolored or damaged', 'reference' => 'ASME B30.16 Sec.2.6.1(d)'],
            '6.8' => ['criteria' => 'Fitted sling or chain would be retained slack in the bowl of the hook where latches are provided', 'reference' => 'ASME B30.16 Sec.1.2.9'],
            '6.9' => ['criteria' => 'Hand operated hoist: Load block is provided with a guard against load chain jamming in the load block under normal operating conditions', 'reference' => 'ASME B30.16 Sec.1.2.10'],
            '6.10' => ['criteria' => 'Electric or Air Powered Hoist: Load block is of the enclosed type and means is provided to guard against rope or load chain jamming in the load block under normal operating conditions', 'reference' => 'ASME B30.16 Sec.1.2.10'],
            '6.11' => ['criteria' => 'Rope is free of damages • Max of 12 randomly broken wires in 1 lay • 4 broken wires in 1 strand of 1 lay • 1 broken wire protruding from the core (2 for rotation resistant ropes) • Wear of 1/3 of the original diameter of outside individual wires Kinking, crushing, birdcaging or other distortion', 'reference' => 'ASME B30.7, Sec.2.4.1(c2)'],
            '6.12' => ['criteria' => 'Rope termination is completed at the hoist wedge anchor with a drop forged U- clip', 'reference' => 'ASME B30.16 Sec.1.2.6'],
            '6.13' => ['criteria' => 'Pendant push buttons that control motion return to off position when pressure is released', 'reference' => 'ASME B30.17 Sec.1.9.3(b2)']
        ]
    ],
    [
        'title' => 'ROPE DRUM',
        'items' => [
            '7.0' => ['criteria' => 'Electric and air powered hoists: Rope drum is grooved and free of surface defects that could cause rope damage (excluding hoists made for special applications)', 'reference' => 'ASME B30.16 Sec.1.2.5'],
            '7.1' => ['criteria' => 'Hoist drum specifications are marked (rated load, drum size, rope size, rope speed (ft/min or m/s), rated power)', 'reference' => 'ASME B30.7 Sec.1.1.3'],
            '7.2' => ['criteria' => 'Hand Chain Hoist: Manufacturer data, serial number and safe working load are clearly displayed on the item', 'reference' => 'ASME B30.16 Sec.1.1.3a'],
            '7.3' => ['criteria' => 'Electric Powered Hoist: Manufacturer data, serial number, safe working load, voltage and phase are clearly displayed on the item', 'reference' => 'ASME B30.16 Sec.1.1.3b'],
            '7.4' => ['criteria' => 'Air Powered Hoist: Manufacturer data, serial number, model, safe working load and rated air pressure are clearly displayed on the item', 'reference' => 'ASME B30.16 Sec.1.1.3c'],
            '7.5' => ['criteria' => 'Warning signs/labels are provided on the hoist units and electrical enclosures', 'reference' => 'ASME B30.16 Sec.1.1.4'],
            '7.6' => ['criteria' => 'Hoist drum is adequately lubricated as per the hoist manufacturers manual', 'reference' => 'ASME B30.16 Sec.2.3.'],
            '7.7' => ['criteria' => 'Drum has a minimum of two wraps of rope on it', 'reference' => 'ASME B30.716 Sec.1.2.6(c)'],
            '7.8' => ['criteria' => 'Structure is vibration free under normal operating condition', 'reference' => 'ASME B30.17 Sec.1.3.1(b)'],
            '7.9' => ['criteria' => 'Monorail end stops are installed and in good condition', 'reference' => 'ASME B30.17 Sec.1.4.2, Sec 1.5.3'],
            '7.10' => ['criteria' => 'Jib crane end stops are installed and in good condition', 'reference' => 'ASME B30.17 Sec.1.4.2, Sec 1.5.3'],
            '7.11' => ['criteria' => 'Tracks are properly installed and aligned', 'reference' => 'ASME B30.17 Sec.1.3.1  Sec 1.4.1'],
            '7.12' => ['criteria' => 'Crane runways or monorail tracks are fastened and Secured to a supporting structure', 'reference' => 'ASME B30.17 Sec.1.3.2'],
            '7.13' => ['criteria' => 'All welded members are free of defects and not corroded', 'reference' => 'ASME B30.17 Sec.1.3.4'],
            '7.14' => ['criteria' => 'Guards protect moving parts such as gears, chains, chain sprockets', 'reference' => 'ASME B30.17 Sec.1.11.1'],
            '7.15' => ['criteria' => 'Guards protect ropes where liable to come in contact with conductors', 'reference' => 'ASME B30.17 Sec.1.11.2(a)'],
            '7.16' => ['criteria' => 'Guards are provided to prevent contact between crane bridge or runway conductors and hoisting ropes', 'reference' => 'ASME B30.17 Sec.1.11.2(b)'],
            '7.17' => ['criteria' => 'Hand chain operated Hoist: Hoist automatically stops and holds lifted load when the actuating force is removed', 'reference' => 'ASME B30.16 Sec.1.2.11a'],
            '7.18' => ['criteria' => 'Electric Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition', 'reference' => 'ASME B30.16 Sec.1.2.11(b1-b)'],
            '7.19' => ['criteria' => 'Air Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition', 'reference' => 'ASME B30.16 Sec.1.2.11(c1-a)'],
            '7.20' => ['criteria' => 'An electric hoist stops and holds the load block in the event of power failure', 'reference' => 'ASME B30.16 Sec.1.2.11(b1-c)'],
            '7.21' => ['criteria' => 'An air hoist stops and holds the load block in the event of air pressure loose', 'reference' => 'ASME B30.16 Sec.1.2.11(c1-b)'],
            '7.22' => ['criteria' => 'Braking systems has means for adjustment to compensate for wear', 'reference' => 'ASME B30.16 Sec.1.2.11(b3/c)'],
            '7.23' => ['criteria' => 'Control circuit voltage does not exceed 600v for AC or DC', 'reference' => 'ASME B30.17 Sec. 1.14.1(b)'],
            '7.24' => ['criteria' => 'Push button enclosure is grounded', 'reference' => 'ASME B30.17 Sec. 1.14.1(e)'],
            '7.25' => ['criteria' => 'Push button enclosure is marked for identification of function', 'reference' => 'ASME B30.17 Sec. 1.14.1(e)'],
            '7.26' => ['criteria' => 'Parts of electrical equipment are enclosed and are not exposed to inadvertent contact under normal operating conditions', 'reference' => 'ASME B30.17 Sec. 1.14.2(a)'],
            '7.27' => ['criteria' => 'Live parts of electrical equipment are protected from direct exposure to grease and oil and protected from dirt and moisture', 'reference' => 'ASME B30.17 Sec. 1.14.2(b)'],
            '7.28' => ['criteria' => 'Guards on live parts are not deformed or/and in contact', 'reference' => 'ASME B30.17 Sec.1.14.2(c)'],
            '7.29' => ['criteria' => 'Floor operated cranes controllers return to off position when released', 'reference' => 'ASME B30.17 Sec.1.14.3(c1)'],
            '7.30' => ['criteria' => 'Pendant push buttons that control motion return to off position when pressure is released', 'reference' => 'ASME B30.17 Sec.1.14.3(c)'],
            '7.31' => ['criteria' => 'Chain passes over all load sprockets without binding', 'reference' => 'ASME B30.16 Sec.1.2.8'],
            '7.32' => ['criteria' => 'Hand Operated Chain: Chain length for extension (stretch) tolerance is no longer than 2.5% of unused chain or as per manufacturer recommendations', 'reference' => 'ASME B30.16 Sec.2.5.2(a)'],
            '7.33' => ['criteria' => 'Power Operated Chain: Chain length for extension (stretch) tolerance is no longer than 1.5% of unused chain or as per manufacturer recommendations', 'reference' => 'ASME B30.16 Sec.2.5.2(a)'],
            '7.34' => ['criteria' => 'The chain does not suffer from gouges, nicks, corrosion, weld spatter or distorted links (Judgement to be used as to the suitability or otherwise of using chain with these deficiencies)', 'reference' => 'ASME B30.16 Sec.2.5.2(b)'],
            '7.35' => ['criteria' => 'The chain does not bind jump or gets noisy when hoist is operated', 'reference' => 'ASME B30.16 Sec.2.6.1(b)'],
            '7.36' => ['criteria' => 'The chain is not stretched or elongated more than 1/4" (6.3 mm) in 12" (305 mm) with reference to the manufacturer\'s manual (roller chain)', 'reference' => 'ASME B30.16 Sec.2.6.1(c1)'],
            '7.37' => ['criteria' => 'The chain is not twisted more than 15 degree in 5 ft (1.52 m) sections (roller chain)', 'reference' => 'ASME B30.16 Sec.2.6.1(c2)'],
            '7.38' => ['criteria' => 'The roller chain pins, links and rollers move freely and are not corroded, pitted, discolored or damaged', 'reference' => 'ASME B30.16 Sec.2.6.1(d)'],
            '7.39' => ['criteria' => 'Fitted sling or chain would be retained slack in the bowl of the hook where latches are provided', 'reference' => 'ASME B30.16 Sec.1.2.9'],
            '7.40' => ['criteria' => 'Hand operated hoist: Load block is provided with a guard against load chain jamming in the load block under normal operating conditions', 'reference' => 'ASME B30.16 Sec.1.2.10'],
            '7.41' => ['criteria' => 'Electric or Air Powered Hoist: Load block is of the enclosed type and means is provided to guard against rope or load chain jamming in the load block under normal operating conditions', 'reference' => 'ASME B30.16 Sec.1.2.10'],
            '7.42' => ['criteria' => 'Rope termination is completed at the hoist wedge anchor with a drop forged U- clip', 'reference' => 'ASME B30.16 Sec.2.5.2(a)'],
            '7.43' => ['criteria' => 'A rope thimble is used in the eye when an eye splice is used in a rope termination (in accordance with the manufacturer\'s instructions)', 'reference' => 'ASME B30.16 Sec.1.2.6'],
            '7.44' => ['criteria' => 'Electric and air powered hoists: Rope drum is grooved and free of surface defects that could cause rope damage (excluding hoists made for special applications)', 'reference' => 'ASME B30.16 Sec.1.2.5'],
            '7.45' => ['criteria' => 'Hoist drum is adequately lubricated as per the hoist manufacturers manual', 'reference' => 'ASME B30.16 Sec.2.3.4'],
            '7.46' => ['criteria' => 'Drum capacity can accommodate the specific rope size and length', 'reference' => 'ASME B30.7 Sec.1.2.2(c)'],
            '7.47' => ['criteria' => 'Drum has a minimum of two wraps of rope on it', 'reference' => 'ASME B30.16 Sec.1.2.6(c)'],
            '7.48' => ['criteria' => 'Each drum end of the rope is anchored by a clamp attached to the drum or by a socket arrangement (approved by the manufacturer)', 'reference' => 'ASME B30.7 Sec.1.2.2(c2)'],
            '7.49' => ['criteria' => 'Drum flanges always extend a minimum of 1/2" (13mm) above the top layer of rope at all times', 'reference' => 'ASME B30.7 Sec.1.2.2(c3)']
        ]
    ],
    [
        'title' => 'HOOKS',
        'items' => [
            '8.0' => ['criteria' => 'Labeling and manufacturer data are available and legible', 'reference' => 'ASME B30.10 Sec.2.1.1'],
            '8.1' => ['criteria' => 'Hook is freely swiveling and lubricated', 'reference' => 'ASME B30.16 Sec.1.2.9'],
            '8.2' => ['criteria' => 'Hook\'s weight is clearly marked/printed on the hook', 'reference' => 'ASME B30.10 Sec.1.1.1'],
            '8.3' => ['criteria' => 'Safe working load is clearly marked on the hook', 'reference' => 'ASME B30.10 Sec2.1.1'],
            '8.4' => ['criteria' => 'Hook is not bent or twisted Max. bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer recommendations', 'reference' => 'ASME B30.10 Sec1.2.1.3(c1)'],
            '8.5' => ['criteria' => 'Hook is not distorted in the throat opening Max. allowable throat opening is 15% compared to new hook, or as per manufacturer recommendations', 'reference' => 'ASME B30.10 Sec.1.2.1.3(c2)'],
            '8.6' => ['criteria' => 'Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations', 'reference' => 'ASME B30.10 Sec.1.2.1.3(c3)'],
            '8.7' => ['criteria' => 'Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations', 'reference' => 'ASME B30.10 Sec.1.2.1.3(c3)'],
            '8.8' => ['criteria' => 'Hook is not cracked, gouged or shows nicks', 'reference' => 'ASME B30.10 Sec1.2.1.2(c3)'],
            '8.9' => ['criteria' => 'Hook can lock (if it is a self-locking hook)', 'reference' => 'ASME B30.10 Sec.1.2.1.3(c4)'],
            '8.10' => ['criteria' => 'Hook latch is operative', 'reference' => 'ASME B30.10 Sec.1.2.1.3(c5)'],
            '8.11' => ['criteria' => 'Hook is free to rotate', 'reference' => 'ASME B30.10 Sec1.2.1.3(c5)']
        ]
    ]
];

$html = '
<style>
    @media print {
        body { margin: 0; padding: 0; }
        .page-break { page-break-before: always; }
        .no-print { display: none; }
        table { border-collapse: collapse; width: 100%; font-size: 12px; }
        th, td { border: 1px solid black; padding: 4px; text-align: left; }
        th { background-color: #f0f0f0; -webkit-print-color-adjust: exact; }
        .checkbox-cell { text-align: center; width: 40px; }
        .section-header { background-color: #e0e0e0; -webkit-print-color-adjust: exact; font-weight: bold; }
        .remarks-table { margin-top: 20px; }
        .signature-table { margin-top: 20px; border: 1px solid black; }
        .signature-table th, .signature-table td { border: 1px solid black; padding: 8px; }
        .footer { margin-top: 20px; }
        .keep-together { page-break-inside: avoid; break-inside: avoid; }
</style>

<table style="width: 100%; border: none; margin-bottom: 20px;">
    <tr>
        <td style="width: 20%; text-align: center;">
            <img src="' . $baseUrl . '/assets/img/logo.png" alt="Company Logo" style="max-width: 100px;">
        </td>
        <td style="width: 60%; text-align: center;">
            <h2 style="margin: 0;">ABC EQUIPMENT INSPECTION SERVICES</h2>
            <p style="margin: 5px 0;">123 Business Street, City, State 12345</p>
            <p style="margin: 5px 0;">Phone: (555) 123-4567 | Email: info@abcinspection.com</p>
        </td>
        <td style="width: 20%; text-align: center;">
            <strong>FRM.0601-1.14<br>Rev.02</strong>
        </td>
    </tr>
</table>

<table style="width: 100%; border: 1px solid black; margin-bottom: 20px;">
    <tr>
        <th style="width: 20%;">Client Name:</th>
        <td style="width: 30%;">' . htmlspecialchars($row['client_name']) . '</td>
        <th style="width: 20%;">Report No:</th>
        <td style="width: 30%;">' . htmlspecialchars($row['report_no']) . '</td>
    </tr>
    <tr>
        <th>Equipment Type:</th>
        <td>A-FRAMES AND MOBILE GANTRIES</td>
        <th>Inspection Date:</th>
        <td>' . htmlspecialchars($row['inspection_date']) . '</td>
    </tr>
    <tr>
        <th>Equipment ID:</th>
        <td>' . htmlspecialchars($row['equipment_id']) . '</td>
        <th>Next Inspection Date:</th>
        <td>' . htmlspecialchars($row['next_inspection_date']) . '</td>
    </tr>
    <tr>
        <th>Standards:</th>
        <td colspan="3">ASME B30.16-2017, ASME B30.17-2015</td>
    </tr>
</table>

<table style="width: 100%; border: 1px solid black;">
    <thead>
        <tr>
            <th style="width: 6%;">S.N</th>
            <th style="width: 38%;">Acceptance Criteria</th>
            <th style="width: 10%;">REF</th>
            <th style="width: 8%; text-align: center;">PASS</th>
            <th style="width: 8%; text-align: center;">FAIL</th>
            <th style="width: 8%; text-align: center;">N/A</th>
            <th style="width: 22%;">Remarks</th>
        </tr>
    </thead>
    <tbody>';

foreach ($sections as $sectionIndex => $section) {
    $html .= '<tr><td colspan="7" class="section-header" style="text-align: center;">' . ($sectionIndex + 1) . '. ' . $section['title'] . '</td></tr>';
    
    foreach ($section['items'] as $itemKey => $item) {
        $resultIndex = array_search($itemKey, array_keys($section['items'])) + array_sum(array_map('count', array_slice($sections, 0, $sectionIndex)));
        
        $html .= '<tr>
            <td style="text-align: center;"><strong>' . $itemKey . '</strong></td>
            <td>' . $item['criteria'] . '</td>
            <td style="text-align: center;">' . $item['reference'] . '</td>
            <td class="checkbox-cell">' . pdf_mark_result($resultIndex, 'PASS', $selected_results) . '</td>
            <td class="checkbox-cell">' . pdf_mark_result($resultIndex, 'FAIL', $selected_results) . '</td>
            <td class="checkbox-cell">' . pdf_mark_result($resultIndex, 'NA', $selected_results) . '</td>
            <td>' . htmlspecialchars($chek_remark[$resultIndex]) . '</td>
        </tr>';
    }
}

$html .= '</tbody>
</table>

<div class="keep-together">
    <div class="remarks-table">
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th colspan="3" style="text-align: center;">REMARKS / RECOMMENDATIONS:</th>
            </tr>
            <tr>
                <td colspan="3" style="height: 80px;">' . htmlspecialchars($recommendations) . '</td>
            </tr>
        </table>
    </div>

    <div class="signature-table">
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th style="width: 25%;">INSPECTOR\'S NAME:</th>
                <td style="width: 25%;"><strong>' . htmlspecialchars($row['inspected_by']) . '</strong></td>
                <th style="width: 25%;">CLIENT\'S REP. NAME:</th>
                <td style="width: 25%;">' . htmlspecialchars($client_name) . '</td>
            </tr>
            <tr>
                <th>SIGNATURE &amp; DATE:</th>
                <td>' . pdf_signature_path($row['inspected_by']) . '</td>
                <th>SIGNATURE &amp; DATE:</th>
                <td><img style="max-width: 60px; max-height: 25px;" src="' . $baseUrl . '/uploads/' . htmlspecialchars($project_no) . '.png" height="50px" width="100px" alt="Client Signature" style="max-width: 60px; max-height: 25px;"></td>
            </tr>
        </table>
    </div>
</div>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('frames-and-mobile-gantries-inspection-checklist.pdf', array('Attachment' => false));
?>