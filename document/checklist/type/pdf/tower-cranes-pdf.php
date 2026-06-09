<?php
include __DIR__ . '/_bootstrap.php';

$project_no = $row['project_no'] ?? '';
$inspector_signature_path = pdf_signature_path($row['inspected_by']);
// $client_signature_path = pdf_asset('client_signature.jpg');
$client_signature_path = $project_no !== '' 
    ? __DIR__ . '/../../../uploads/' . $project_no . '.png' 
    : '';

$sections = [
    'MARKINGS, DOCUMENTS' => [
        'Documentation is available such as but not limited to; operator’s manual, manufacturer’s informal literature, etc.',
        'An installation preparation instruction is provided.',
        'Structure or anchor has the information data plate bearing the Manufacturer Name, Type/Model Number, Serial Number, & Year of Manufacture.',
        'Structure has an identification number / asset number marked on it.',
        'Crane’s SWL (Rated Load) is prominently marked on the structure.',
        'Load Rating chart of the crane is provided.',
        'General erection & dismantling requirements are met (Drawings & Calculations).',
        'The crane is operated by the qualified, competent, or certified operator.'
    ],
    'INSPECTION & TESTING' => [
        'Structures such as but not limited to, tower masts, knee braces, cross beams, climbing ladders, climbing cross sections have no signs of cracks, corrosions, bends, deformations.',
        'Tie-in braces and pins are secured.',
        'Climbing pawls and wedges are secured.',
        'Tower’s anchor bolts at base are properly mounted and secured.',
        'Expendable base and knee-braced base are installed properly.',
        'Load & luffing/jib boom hoist drives are provided with a clutch or power disengaging device unless directly coupled to an electric or hydraulic power motor source.',
        'All functions are checked and working correctly, i.e., but not limited to, luffing/jib booms’ hoisting & lowering, structure’s slewing, load block’s lowering & hoisting, trolley traversing.',
        'Motion limiting devices and brakes of load hoist, luffing/jib boom hoist are checked.',
        'All controls, drives, and braking means devices are checked which include; load block hoisting & lowering; luffing boom hoisting and lowering; swinging of the upper structure; brake and clutch functioning; limit, locking, and safety device functioning; and load-limiting devices for proper operation.',
        'Over-speed protection is provided for hoist and luffing boom mechanisms.',
        'Luffing/jib boom and load hoist free-fall lowering is not provided. Ensure that they shall be done only under power control.',
        'Luffing/jib hoist powered by hydraulic is not dropping.',
        'Luffing boom back stop switch is provided for the maximum boom angle.',
        'The luffing/jib hoist rope is securely anchored on the drum as per the manufacturer recommendation.',
        'The diameter of the drum is sufficient to provide a first layer rope pitch diameter of not less than 18 times the nominal diameter of the rope used.',
        'The remaining rope on load hoist drum shall not be less than three full wraps when the hook is in its extreme lowest position.',
        'The remaining rope on luffing/jib boom hoist shall not be less than three full wraps when the luffing/jib boom is at its maximum permissible radius.',
        'Load hoist drum and luffing boom hoist drums are provided with a positive holding device, such as ratchets and pawls, unless directly coupled to electric or hydraulic drives.',
        'Positive holding devices are controlled only from the operator’s station; hold the drums from rotating in the lowering direction, and capable of holding the rated load indefinitely, or luffing boom and rated load indefinitely, as applicable without further attention from the operator.',
        'Luffing boom hoist rope and load hoist rope shall be equipped with at least one braking means that is capable of providing minimum of 125 % of the full load hoisting torque at the point of where the braking is applied.',
        'A secondary emergency brake is provided on the luffing boom hoist drum for use in the event of a main drive failure.',
        'Load hoist and luffing boom hoist mechanisms are equipped with braking means capable of providing controlled lowering speeds.',
        'An automatic means is provided for controlling the load hoist or the luffing boom hoist to stop and hold the load in the event of loss of brake actuating power.',
        'If foot pedal is provided, it is holding the brakes in the applied position without further attention from the operator.',
        'Sheave bearings are provided with a means for lubrication, except for those that are permanently lubricated.',
        'The pitch diameter of the load block sheaves are not less than 18 times the nominal diameter of the rope used.',
        'The pitch diameter of luffing boom hoist sheaves are not less than 15 times the nominal diameter of the rope used.',
        'The load block sheaves are equipped with close fitting guard to prevent ropes from becoming fouled when the block is lying on the ground.',
        'Rope end socketing is as per the manufacturer.',
        'Rotation-resistant rope is not used for luffing boom hoist.',
        'Design factor for luffing boom hoist rope is not less than 3.5',
        'Design factor for load hoist rope is not less than 5.',
        'Load hook is equipped with safety latches and working properly.',
        'No pitting or corrosion is visible.',
        'No signs of cracks, nicks, or gouges are visible.',
        'Load hook is marked with its SWL and weight.',
        'The wear on the hook does not exceed 10% from the original.',
        'There is no deformation that is visibly apparent bend or twist from the plane of the unbent hook.',
        'No any distortion causing an increase in the throat opening of 5% that exceeded ¼ in. (6mm) or as recommended by the manufacturer.',
        'Self-locking hook is able to lock.',
        'No damaged, missing, or malfunctioning hook attachment.',
        'No thread wear or corrosion is evident.',
        'No evidence of heat exposure or unauthorized welding.',
        'No evidence of unauthorized alteration such as drilling, machining, grinding or other modifications.',
        'Swing mechanism is capable of smooth starts and stops and of providing variable degrees of acceleration and deceleration.',
        'Crane is equipped with means to rotate freely when it is out of service in order to weathervane.',
        'Braking means with holding power in both directions is provided.',
        'Brakes apply automatically on loss of electrical power or actuating force to the brake.',
        'Travel drives are capable of smooth starts and stops, and providing variable degrees of acceleration and deceleration.',
        'Cable spooling is provided.',
        'Audible signal automatically sounds continuously whenever the crane travels.',
        'Crane bogies are fitted with sweeps at each end of the bogie and extending below the top of the rail.',
        'Bogie wheels are guarded.',
        'Means are provided to limit the drop of bogie frames to a distance that will not cause the crane to overturn in case of wheel or axle breakage.',
        'Braking means are provided to hold the crane In position when not travelling and to lock the wheels against rotation.',
        'Brakes automatically engaged on loss of electrical power or actuating force to the brake.',
        'Guides are provided to hold the ladders in position for engagement of the climbing dogs.',
        'Hydraulic cylinders used to support the crane during climbing are equipped with check valves.',
        'Hydraulic system is provided with pressure gauges and over pressure relief valves.',
        'Positive means to hold the raised portion of the crane in position at the completion of an intermediate climbing step.',
        'Pressurized hydraulic cylinders are not used to support the crane when in service.',
        'Wedges when used shall be provided with means to hold them in place and prevent them from becoming dislodged.',
        'Ropes have a minimum breaking force not less than 3.5 times the load applied to the rope.',
        'Trolley is capable of smooth starts and stops and providing variable degrees of acceleration and deceleration when traversing the jib during operations.',
        'Trolley stops or buffers are provided on both ends of the jib.',
        'The body or frame of the trolley is fitted with means to retrain the trolley from becoming detached from its guide rails.',
        'Braking means is provided and capable of stopping in both directions.',
        'A brake is holding the trolley without further action when power or pressure is lost.',
        'Trolley is equipped with an automatic braking device in case of the rope breakage.'
    ],
    'OPERATOR AIDS' => [
        'Indicating device shall be provided to display the load on the hook.',
        'Indicating device shall be provided to display the luffing boom angle, hook radius, or trolley operating radius, as appropriate.',
        'Indicating device shall be provided to display the ambient wind velocity',
        'Limiting device shall be provided to decelerate the trolley travel at both ends of the jib prior to final limit activation.',
        'Limiting device shall be provided to decelerate the luffing boom travel at minimum and maximum radius prior to final limit activation.',
        'Limiting device shall limit trolley travel at both ends of the jib.',
        'Limiting device shall stop the luffing boom travel at minimum and maximum radius of luffing boom.',
        'Limiting device shall decelerate the load block travel prior to final limit activation.',
        'Limiting device shall stop load block upward motion before two blocking occurs.',
        'Limiting device shall stop load block downward motion to prevent from spooling off the drum.',
        'Limiting device shall limit the crane travel at both ends of the running tracks.',
        'Limiting device shall limit the load lifted.',
        'Limiting device shall limit operating radius in accordance with crane’s rated capacity, i.e. load moment.',
        'Limiting device shall limit pressures in hydraulic or pneumatic circuits.',
        'Motion limiting devices, should be provided with means to permit the operator to override them under controlled conditions.'
    ],
    'PENDANTS, STAY ROPES, AND GUYS, COUNTERWEIGHTS, COUNTER JIBS' => [
        'Fiber core ropes with swayed fittings and rotation-resistant ropes shall not be used for pendants, guy ropes and stay ropes.',
        'Rotation-resistant ropes shall be used for luffing boom.',
        'Wire rope clips are drop-forged steel of the single (U-bolt) or double saddle type clip.',
        'Means to prevent the shifting or dislodgement of superstructure and counterjib’s counter weight during crane operation is provided.',
        'Counterweights and ballast blocks are individually marked with their actual weights and visible when they are in installed position.',
        'Only steel-framed concrete or solid steel counterweights suspended from the superstructure are used.',
        'Movable counterweights, if provided, are moving automatically.',
        'Means to prevent uncontrolled movement in the event of rope failure for counterweights controlled by ropes is provided.',
        'Controls are within the reach of the operator.',
        'All controls are labeled of their mode of functions.',
        'Hoisting, trolleying, luffing, slewing, and travel motions are stopping when control actuation pressure is released.',
        'An interlock that prevents the re-actuation, except from the neutral position, of controls is provided.',
        'The crane stops when signal is lost for remote operated cranes.',
        'The device that will disconnect all motors from the line on failure of power and will not permit any motor to be restarted until the operational control is brought to the neutral position and a manual reset is activated is provided for electric motor powered cranes.',
        'An electric motor powered crane is provided with means for operator to interrupt the main power circuit from the operating position.',
        'A remote control station is provided with emergency stop button.',
        'Simultaneous activation of controls is not possible when more than one operator’s station (remote control) is provided.',
        'Cranes powered by hydraulic motors shall stop the main power supply system when hydraulic pressure is lost.',
        'Controls for the main power supply system shall be within the reach of the operator, and will include the following: controlling the speed of the engine, means to control in stopping the engine, means for shifting the transmission’s gear selection.',
        'Cabs should be provided for the operator’s station.',
        'Cab doors are opening outward or sliding.',
        'An adjustable operator seat is provided.',
        'Windshield is of safety glazing glass.',
        'The operator cab shall be on the operating portion of the crane.',
        'An access ladder to the cab is provided.',
        'Outside platforms have walking surfaces of a skid resistant type.',
        'Tool box is available for storage of small tools.',
        'Fire extinguisher with a basic minimum classification of 10-BC is provided in the cab or at the machinery housing..',
        'Footwalks and ladders: 18in. or more in width and a slip resistant surface and with handrails or a platform attached to the trolley having a slip resistant surface and handrails.',
        'Guards are installed for exposed moving parts such as gears, drive chains, sprockets, and other rotating parts.',
        'Each guard shall be capable of supporting the weight of a 300-lb (136 kg) person without permanent distortion.',
        'Lubrication points should be accessible without the necessity of removing guards or other parts with tools unless equipped with centralized lubrication.',
        'Engine exhaust gas is to be piped and discharged away from the operator’s cabin.',
        'Dry friction clutches are protected against rain and other liquids, such as oil and lubricants.',
        'Clutches are configured to permit adjustments where necessary to compensate wear.',
        'An anemometer is installed.(Wind Velocity Device)',
        'Fuel tank filler pipes are located or protected to prevent spillage or overflow.',
        'Relief valves are provided in hydraulic and pneumatic circuits carrying fluids pressurized by a power driven pump.',
        'Means to prevent unauthorized adjustment or tampering is provided.',
        'Means for checking the manufacturer’s specified pressure settings in each circuit is provided.',
        'Ropes have no loss of rope diameter in a short rope length or unevenness of outer strands.',
        'Rope has broken or cut strands.',
        'In running ropes, 12 randomly distributed broken wires in one lay, or four broken wires in one strand in one lay.',
        'In rotation-resistant ropes, two randomly distributed broken wires in six rope diameters, or four randomly distributed broken wires in 30 rope diameters.',
        'One outer wire broken at the contact point with the core of the rope indicated by an externally protruding wire or loop of loose wires.',
        'Wear of one-third the original diameter of outside individual wires.',
        'Kinking, crushing, birdcaging, or any other damage resulting to distortion of the rope structure.',
        'Evidence of heat damage from any cause.',
        'Reduction from nominal diameter greater than 5%.',
        'More than two broken wires adjacent to the socketed end connection, the rope shall be re-socketed or replaced.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPECTION CHECKLIST FOR TOWER CRANES</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 100px; }
        .header h2 { margin: 5px 0; }
        .header p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; vertical-align: top; }
        th { background-color: #f0f0f0; }
        .checkbox-cell { text-align: center; }
        .custom-checkbox { width: 15px; height: 15px; }
        .tick { font-size: 18px; color: green; }
        .cross { font-size: 18px; color: red; }
        .signature-section { margin-top: 40px; }
        .signature-section table { width: 100%; }
        .signature-section th, .signature-section td { border: none; padding: 10px; }
        .keep-together { page-break-inside: avoid; break-inside: avoid; }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?php echo pdf_asset('logo.png'); ?>" alt="Logo">
        <h2>INSPECTION CHECKLIST FOR TOWER CRANES</h2>
        <p>ASME B30.3-2016</p>
    </div>

    <table>
        <tr>
            <th style="background-color: #c0d6e8 !important;">REPORT NO</th>
            <td><strong><?php echo htmlspecialchars($row['report_no']); ?></strong></td>
            <th style="background-color: #c0d6e8 !important;">INSPECTION DATE</th>
            <td><strong><?php echo htmlspecialchars($row['inspection_date']); ?></strong></td>
        </tr>
        <tr>
            <th style="background-color: #c0d6e8 !important;">CLIENT'S NAME</th>
            <td><strong><?php echo htmlspecialchars($row['client_name']); ?></strong></td>
            <th style="background-color: #c0d6e8 !important;">INSPECTED BY</th>
            <td><strong><?php echo htmlspecialchars($row['inspected_by']); ?></strong></td>
        </tr>
        <tr>
            <th style="background-color: #c0d6e8 !important;">LOCATION</th>
            <td><strong><?php echo htmlspecialchars($row['location']); ?></strong></td>
            <th style="background-color: #c0d6e8 !important;">STICKER NO.</th>
            <td><strong><?php echo htmlspecialchars($row['sticker_no']); ?></strong></td>
        </tr>
        <tr>
            <th style="background-color: #c0d6e8 !important;">EQUIPMENT NO</th>
            <td><strong><?php echo htmlspecialchars($row['equipment_no']); ?></strong></td>
            <th style="background-color: #c0d6e8 !important;">EQUIP.SERIAL NO.</th>
            <td><strong><?php echo htmlspecialchars($row['crane_serial_no']); ?></strong></td>
        </tr>
        <tr>
            <th style="background-color: #c0d6e8 !important;">EQUIPMENT TYPE</th>
            <td><strong><?php echo htmlspecialchars($row['equipmenttype']); ?></strong></td>
            <th style="background-color: #c0d6e8 !important;">CAPACITY (SWL)</th>
            <td><strong><?php echo htmlspecialchars($row['capacity_swl']); ?></strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;">S.N</th>
                <th style="text-align: center;">ACCEPTANCE CRITERIA</th>
                <th style="text-align: center;">REFERENCE</th>
                <th style="text-align: center;" colspan="3">RESULT</th>
                <th style="text-align: center;">REMARKS</th>
            </tr>
            <tr>
                <th style="text-align: center;"></th>
                <th style="text-align: center;"></th>
                <th style="text-align: center;"></th>
                <th style="text-align: center;">PASS</th>
                <th style="text-align: center;">FAIL</th>
                <th style="text-align: center;">NA</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 0;
            foreach ($sections as $section_title => $items) {
                echo "<tr><th colspan='7' style='text-align: center; background-color: #c0d6e8;'>{$section_title}</th></tr>";
                foreach ($items as $item) {
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>";
                    echo "<td>{$item}</td>";
                    echo "<td></td>";
                    echo "<td class='checkbox-cell'>" . pdf_mark_result($index, 'PASS', $selected_results) . "</td>";
                    echo "<td class='checkbox-cell'>" . pdf_mark_result($index, 'FAIL', $selected_results) . "</td>";
                    echo "<td class='checkbox-cell'>" . pdf_mark_result($index, 'NA', $selected_results) . "</td>";
                    echo "<td>" . htmlspecialchars($chek_remark[$index]) . "</td>";
                    echo "</tr>";
                    $index++;
                }
            }
            ?>
        </tbody>
    </table>

    <div class="keep-together">
    <table>
        <tbody>
            <tr>
                <th colspan="3" style="text-align: center;">REMARKS / RECOMMENDATIONS:</th>
            </tr>
            <tr>
                <td style="height: 120px;" colspan="3">
                    <?php echo htmlspecialchars($recommendations); ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <table>
            <tr>
                <th style="width: 25%;">INSPECTOR’S NAME:</th>
                <td style="width: 25%;">
                    <strong><?php echo htmlspecialchars($row['inspected_by']); ?></strong>
                </td>
                <th style="width: 25%;">CLIENT’S REP. NAME:</th>
                <td style="width: 25%;">
                    <?php echo htmlspecialchars($client_name); ?>
                </td>
            </tr>
            <tr>
                <th>SIGNATURE & DATE:</th>
                <td>
                    <?php if (file_exists($inspector_signature_path)) { ?>
                        <img src="<?php echo $inspector_signature_path; ?>" alt="Inspector Signature" style="max-width: 50px; max-height: 25px;">
                    <?php } ?>
                </td>
                <th>CLIENT’S REP. SIGNATURE & DATE:</th>
                <td>
                    <?php if (file_exists($client_signature_path)) { ?>
                        <img src="<?php echo $client_signature_path; ?>" alt="Client Signature" style="max-width: 50px; max-height: 25px;">
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
    </div>
</body>
</html>