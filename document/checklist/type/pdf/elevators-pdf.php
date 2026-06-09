<?php
include_once('./../../_bootstrap.php');

$idchecklistview = $_GET['idchecklistview'];
$result = $conn->query("select * from checklistlog where idchecklistlog=$idchecklistview");
$row = $result->fetch_assoc();

$selected_results = isset($_GET['selected_results']) ? json_decode($_GET['selected_results'], true) : array_fill(0, 300, '');
$chek_remark = isset($_GET['remarks']) ? json_decode($_GET['remarks'], true) : array_fill(0, 300, '');

// Elevator checklist structure
$sections = [
    [
        'number' => '1',
        'title' => 'HYDRAULIC ELEVATOR',
        'ref' => 'ASME A17.1',
        'items' => [
            [
                'number' => '1.1',
                'title' => 'INSIDE OF CAR',
                'ref' => '',
                'subitems' => [
                    ['number' => '1.1.1', 'text' => 'Door reopening device is operating correctly', 'ref' => 'ASME A17.1 Sec. (2.13(3.13), 8.11.2.1.1a, 8.11.3.1.1a)'],
                    ['number' => '1.1.2', 'text' => 'Emergency stop switches are not provided on passenger elevators but are provided on freight elevators, in the car and in or adjacent to each car operating panel', 'ref' => 'ASME A17.1 Sec. (3.26.4.2a, 3.26.4.2f, 8.11.3.1.1b)'],
                    ['number' => '1.1.3', 'text' => 'All operating control devices are of the enclosed electric type', 'ref' => 'ASME A17.1 Sec. (2.26.1.1(3.26.1), 3.26.3, 8.11.3.1.1c)'],
                    ['number' => '1.1.4', 'text' => 'Sills are of the correct type and are of sufficient strength and clearance with adjoining car platform or hoist way sill (min. clearance 13mm)', 'ref' => 'ASME A17.1 Sec. (2.5.1(3.5), 2.11.10.3 (3.11), 2.11.11.1, 2.11.13.1, 2.15.16 (3.15), 8.11.3.1.1d)'],
                    ['number' => '1.1.5', 'text' => 'Door reopening device is operating correctly', 'ref' => 'ASME A17.1 Sec. (2.13(3.13), 8.11.2.1.1a, 8.11.3.1.1a)'],
                    ['number' => '1.1.6', 'text' => 'Emergency stop switches are not provided on passenger elevators but are provided on freight elevators, in the car and in or adjacent to each car operating panel', 'ref' => 'ASME A17.1 Sec. (3.26.4.2a, 3.26.4.2f, 8.11.3.1.1b)'],
                    ['number' => '1.1.7', 'text' => 'All operating control devices are of the enclosed electric type', 'ref' => 'ASME A17.1 Sec.(2.26.1.1(3.26.1), 3.26.3, 8.11.3.1.1c)'],
                    ['number' => '1.1.8', 'text' => 'Sills are of the correct type and are of sufficient strength and clearance with adjoining car platform or hoist way sill (min. clearance 13mm)', 'ref' => 'ASME A17.1 Sec. (2.5.1(3.5), 2.11.10.3 (3.11), 2.11.11.1, 2.11.13.1, 2.15.16']],
                    ['number' => '1.1.9', 'text' => 'Car has minimum of two lamps (min. of 50 lux for passenger and 25 lux for freight elevators) (Passenger elevators shall have auxiliary lighting which automatically turns on if normal power fails)', 'ref' => 'ASME A17.1 Sec. (3.14, 8.11.3.1.1e)'],
                    ['number' => '1.1.10', 'text' => 'Car emergency communication signal to authorized and emergency personnel is available and working', 'ref' => 'ASME A17.1 Sec. (2.27.1 (3.27), 8.11.3.1.1f)'],
                    ['number' => '1.1.11', 'text' => 'Each car door or gate has electric contacts or interlocks (where required) to prevent operation of the driving machine when the door or gate is open', 'ref' => 'ASME A17.1 Sec. (2.12.7.3 (3.12), 2.13.2.1 (3.13), 2.14.4, 2.14.6 (3.14), 2.26.2)'],
                    ['number' => '1.1.12', 'text' => 'Force required to prevent door closing does not exceed 30 ft.lb', 'ref' => 'ASME A17.1 Sec. (2.13.4.2.3, 8.11.3.1.1h)'],
                    ['number' => '1.1.13', 'text' => 'An Identification Plate is provided with the following items are clearly marked: Manufacturer name & address, weight of the empty platform, date of manufacture, number of personnel allowed on the platform, certificate number of compliance to the design, construction and testing', 'ref' => 'ASME A17.1 Sec. (2.13.3 (3.13), 8.11.3.1.1i)'],
                    ['number' => '1.1.14', 'text' => 'Power opening of doors or gates only occurs when the car is at rest at the landing, or in the landing zone', 'ref' => 'ASME A17.1 Sec. (2.12.5 (3.12), 2.26.1.6, 2.26.9 (2.26.9.3), 3.26.3, 8.11.3.1.1j)'],
                    ['number' => '1.1.15', 'text' => 'Car vision panels and glass car doors meet specifications (not more than 0.1 sq. m. and no panel more than 150mm wide, glass to be laminated or safety glass or safety plastic)', 'ref' => 'ASME A17.1 Sec. (2.14.2.5, 2.14.5.8 (3.14), 8.11.3.1.1k)'],
                    ['number' => '1.1.16', 'text' => 'Car enclosure is in compliance with the required equipment (specification)', 'ref' => 'ASME A17.1 Sec. (2.14 (3.14), 2.29.1 (3.27), 8.3.7, 8.7.2.14, 8.7.3.13, 8.11.3.1.1l)'],
                    ['number' => '1.1.17', 'text' => 'Ventilation (natural or forced) complies with the various opening and size requirements as well as air change volume per minute (for forced ventilation)', 'ref' => 'ASME A17.1 Sec. (2.14.2.3, 2.14.3.3 (3.14), 8.11.3.1.1n)'],
                    ['number' => '1.1.18', 'text' => 'Signs and operating device symbols are installed and legible', 'ref' => 'ASME A17.1 (2.26.12, 8.11.3.1.1b)'],
                    ['number' => '1.1.19', 'text' => 'Signs and operating device symbols are installed and legible', 'ref' => 'ASME A17.1 Sec. (2.26.12, 8.11.3.1.1b)'],
                    ['number' => '1.1.20', 'text' => 'Rated load, platform area and data plate are available and legible', 'ref' => 'ASME A17.1 Sec. (2.16 (3.16), 8.11.3.1.1p)'],
                    ['number' => '1.1.21', 'text' => 'Standby power operation (at least one elevator at a time) with rated load in the event of power supply failure (transfer from normal to standby supply is automatic)', 'ref' => 'ASME A17.1 Sec. (2.27.2 (3.27), 8.11.2.2.7 (8.11.3.2.3f), 8.11.3.1.1q)'],
                    ['number' => '1.1.22', 'text' => 'Restricted opening of car or hoist way doors (4" max) is possible outside the unlocking zone', 'ref' => 'ASME A17.1 Sec. (2.12.5 (3.12), 8.11.3.1.1r)'],
                    ['number' => '1.1.23', 'text' => 'Car ride is smooth in acceleration and deceleration throughout its travel', 'ref' => 'ASME A17.1 Sec. (3.15, 3.23.1, 8.6.1.6.2 (8.6.5), 8.11.3.1.1s)'],
                ]
            ],
            [
                'number' => '1.2',
                'title' => 'MACHINE ROOM',
                'ref' => '',
                'subitems' => [
                    ['number' => '1.2.1', 'text' => 'Access to the machine space is in conformance with the type of access, location, and combustibility allowed', 'ref' => 'ASME A17.1 Sec. (3.1, 3.7, 8.11.3.1.2a)'],
                    ['number' => '1.2.2', 'text' => 'Minimum headroom clearance is either 84", 53", 42", or 35" depending on type and location of machine room / hoist way', 'ref' => 'ASME A17.1 Sec. (2.4.7 (3.7), 8.11.3.1.2b)'],
                    ['number' => '1.2.3', 'text' => 'Electric lighting in the machine room is not less than 200 lux at floor level and the control switch is at the lock - jamb side of the access door wherever practicable', 'ref' => 'ASME A17.1 Sec. (2.7.5.1 (3.7), 8.11.3.1.2c)'],
                    ['number' => '1.2.4', 'text' => 'Strength and construction of the floor of the machine room, windows, skylights and fire resistance is in accordance with the relevant building code', 'ref' => 'ASME A17.1 Sec. (2.7.1.1 (3.7), 2.9.2, 2.9.4 (3.9), 8.11.3.1.2d)'],
                    ['number' => '1.2.5', 'text' => 'Housekeeping is adequate', 'ref' => 'ASME A17.1 Sec. (8.6.1.2, 8.6.4.8 (8.6.5), 8.6.10.3, 8.11.3.1.2e)'],
                    ['number' => '1.2.6', 'text' => 'Ventilation (natural or forced) complies with the various opening and size requirements as well as air change volume per minute (for forced ventilation)', 'ref' => 'ASME A17.1 Sec. (2.7.5.2 (3.7), 2.8.4, 8.11.3.1.2f)'],
                    ['number' => '1.2.7', 'text' => 'Fire extinguisher is available in the machine room (Class ABC)', 'ref' => 'ASME A17.1 Sec. (8.11.3.1.2g, (8.6.5))'],
                    ['number' => '1.2.8', 'text' => 'Pipes, wiring and ducts conform to the relevant specification (Pipes - 15psi steam or hot water only; wiring to NFPA70 or CSA-C22.1 standard)', 'ref' => 'ASME A17.1 Sec. (2.8.1, 2.8.2 (3.8), 8.11.3.1.2h)'],
                    ['number' => '1.2.9', 'text' => 'Guarding of exposed auxiliary equipment is in place and secure', 'ref' => 'ASME A17.1 Sec. (2.10.1 (3.10), 8.11.3.1.2i)'],
                    ['number' => '1.2.10', 'text' => 'Verify numbering of elevators (min. 50mm height figures) on driving machine, disconnect switch, mg set, controller, selector, governor and the car crosshead or frame', 'ref' => 'ASME A17.1 Sec. (2.10.4.2, 2.29.1 (3.27), 3.26)'],
                    ['number' => '1.2.11', 'text' => 'Electrical disconnecting means (devices) and controls operate correctly', 'ref' => 'ASME A17.1 Sec. (3.26, 3.26.3.1 (3.26.3.1.4b), 8.11.3.1.2k)'],
                    ['number' => '1.2.12', 'text' => 'Controller wiring, fuses, grounding, etc. conform to NFPA 70 or CSA C22.1', 'ref' => 'ASME A17.1 Sec. (2.8.1 (3.8), 3.26, 3.26.5, 8.6.1.6.3, 8.6.5, 8.11.3.1.2l)'],
                    ['number' => '1.2.13', 'text' => 'Governor, over speed switch and seal conform to requirements: namely, an over speed switch on every car and counterweight governor, sealing of the means to regulate the governor rope pull-out force (carrier) once set, to not more than 60% of the pull through', 'ref' => 'ASME A17.1 Sec. (2.17, 2.18, 3.17.1, 8.6.1.2, 8.7.2.19, 8.11.2.2.2, 8.11.3.2.3)'],
                    ['number' => '1.2.14', 'text' => 'Code date plate states correct information and is legible', 'ref' => 'ASME A17.1 Sec. (8.7.1.8, 8.9)'],
                    ['number' => '1.2.15', 'text' => 'Hydraulic power unit is operational, undamaged and does not leak', 'ref' => 'ASME A17.1 Sec. (3.24, 8.6.5, 8.11.3.1.2m)'],
                    ['number' => '1.2.16', 'text' => 'Hydraulic relief valve(s) are fitted between the pump and check valve and are of sufficient capacity to pass the rated capacity of the pump without raising working pressure more than 50% above normal (valve should be sealed)', 'ref' => 'ASME A17.1 Sec. (3.19.1, 3.19.2, 3.19.4.2, 3.28, 8.10.3.2.2m, 8.11.3.2.1)'],
                    ['number' => '1.2.17', 'text' => 'Hydraulic control valve(s) are marked with their rated pressure and electrical data', 'ref' => 'ASME A17.1 Sec. (3.19, 8.11.3.1.2o)'],
                    ['number' => '1.2.18', 'text' => 'Oil tanks are of sufficient capacity to provide reserve liquid, prevent ingress of air and be clearly marked with minimum level', 'ref' => 'ASME A17.1 Sec. (3.24, 8.6.5.1, 8.6.5.2, 8.6.5.5, 8.6.5.6, 8.7.3.29, 8.11.3.1.2p, 8.11.3.3.2)'],
                    ['number' => '1.2.19', 'text' => 'Flexible hydraulic hoses and fitting assemblies are undamaged and leak-free', 'ref' => 'ASME A17.1 Sec. (3.19.3.3, 8.11.3.1.2q, 8.11.3.2.4)'],
                    ['number' => '1.2.20', 'text' => 'Supply line and shutoff line are leak-free, and the shut-off valve is located between pump and jack and outside the hoist way', 'ref' => 'ASME A17.1 Sec. (3.19, 8.11.3.1.2r)'],
                    ['number' => '1.2.21', 'text' => 'Hydraulic cylinders are free from damage and are leak-free', 'ref' => 'ASME A17.1 Sec. (3.18.3, 8.11.3.1.2s, 8.11.3.2.2)'],
                    ['number' => '1.2.22', 'text' => 'Pressure switch is fitted if the top of the cylinder is above the top of the storage tank in line between cylinder and valve, the latter activating on loss of positive pressure at the top of the cylinder', 'ref' => 'ASME A17.1 Sec. (3.26.8, 8.11.3.1.2t, 8.11.3.2.5)'],
                    ['number' => '1.2.23', 'text' => 'Pressure switch prevents automatic door opening and the operation of the lowering valve(s) (Car doors can be opened when in the unlocking zone using the in-car button)', 'ref' => 'ASME A17.1 Sec. (3.26.8, 8.11.3.1.2t, 8.11.3.2.5)'],
                ]
            ],
            [
                'number' => '1.3',
                'title' => 'TOP OF CAR',
                'ref' => '',
                'subitems' => [
                    ['number' => '1.3.1', 'text' => 'Car top stop switch is provided and operational', 'ref' => 'ASME A17.1 Sec. (3.26.4, 8.11.3.1.3a)'],
                    ['number' => '1.3.2', 'text' => 'Car top light and outlet is provided and operational', 'ref' => 'ASME A17.1 Sec. (2.14.7 (3.14), 8.11.3.1.3b)'],
                    ['number' => '1.3.3', 'text' => 'Car top operating device is provided (for inspection purposes)', 'ref' => 'ASME A17.1 Sec. (3.26.2, 8.11.3.1.3c)'],
                    ['number' => '1.3.4', 'text' => 'Car top clearance and refuge space dimensions: varies for the former: minimum 43" for the latter', 'ref' => 'ASME A17.1 Sec. (3.4, 3.18.4, 8.10.3.2.2s, 8.10.3.2.3d, 8.11.3.1.3d)'],
                    ['number' => '1.3.5', 'text' => 'Terminal stopping devices are provided and arranged to slow down and stop the car automatically at or near the top and bottom terminal landings (with up to rated load) and at a speed attained in normal operation', 'ref' => 'ASME A17.1 Sec. (3.25.1.1, 8.10.2.3.2k, 8.11.2.2.5 (8.11.3.2.3), 8.11.3.1.3e)'],
                    ['number' => '1.3.6', 'text' => 'Final terminal stopping devices are electro-mechanically operated and cause power to the driving machine motor to be removed automatically after the car has passed a terminal landing', 'ref' => 'ASME A17.1 Sec. (2.7.5.2 (3.7), 2.8.4, 8.11.3.1.2f)'],
                    ['number' => '1.3.7', 'text' => 'Anti-creep device controls the car within 25mm of the landing irrespective of hoist way door position', 'ref' => 'ASME A17.1 Sec. (3.26.3, 3.26.4, 8.11.3.1.3g)'],
                    ['number' => '1.3.8', 'text' => 'Top emergency exit is at least 16" square', 'ref' => 'ASME A17.1 Sec. (2.14.1.5 (3.14), 8.11.3.1.3i)'],
                    ['number' => '1.3.9', 'text' => 'Verify floor level and emergency identification numbering of elevators (min. 50mm height)', 'ref' => 'ASME A17.1 Sec. (2.29.1 (3.27), 2.29.2 (3.1), 8.11.3.1.3j)'],
                    ['number' => '1.3.10', 'text' => 'Hoist way construction complies with appropriate standards and building regulations (where applicable)', 'ref' => 'ASME A17.1 Sec. (3.1, 8.11.3.1.3k)'],
                    ['number' => '1.3.11', 'text' => 'Hoist way smoke control arrangements are satisfactory enough to prevent the accumulation of smoke and hot gases', 'ref' => 'ASME A17.1 Sec. (2.1.4 (3.1), 8.11.3.1.3l)'],
                    ['number' => '1.3.12', 'text' => 'Pipes, wiring and ducts conform to the relevant specification (Pipes - 15psi steam or hot water only; wiring to NFPA70 or CSA-C22.1 standard)', 'ref' => 'ASME A17.1 Sec. (2.8(3.8), 8.11.3.1.3m)'],
                    ['number' => '1.3.13', 'text' => 'Windows, projections, recesses and setbacks comply with the appropriate building codes and hoist way enclosures generally have flush surfaces on the hoist way side', 'ref' => 'ASME A17.1 Sec. (2.1.5, 2.1.6 (3.1), 2.11.10 (3.11), 8.11.3.1.3n)'],
                    ['number' => '1.3.14', 'text' => 'Various hoist way clearances are at least the same all the way around (20mm)', 'ref' => 'ASME A17.1 Sec. (2.5(3.5), 2.11 (3.11), 8.11.3.1.3o)'],
                    ['number' => '1.3.15', 'text' => 'Multiple hoist ways (and the number of elevators in a hoist way) conforms with the appropriate building code', 'ref' => 'ASME A17.1 Sec. (2.1.1.4 (3.1), 8.11.3.1.3p)'],
                    ['number' => '1.3.16', 'text' => 'Traveling cables and junction boxes conforms to NFPA70 or CSA - C22.1, whichever is applicable', 'ref' => 'ASME A17.1 Sec. (2.8.1 (3.8), 8.11.3.1.3q)'],
                    ['number' => '1.3.17', 'text' => 'Door and gate equipment operation are satisfactory and in accordance with manufacturers recommendations', 'ref' => 'ASME A17.1 Sec. (2.11 (3.11), 2.12 (3.12), 2.26.1.6 (3.26.3), 8.11.3.1.3r)'],
                    ['number' => '1.3.18', 'text' => 'Car frame and stiles are suitable for the purpose and show no defects', 'ref' => 'ASME A17.1 Sec. (3.15, 8.8 (3.18.5), 8.11.3.1.3s)'],
                    ['number' => '1.3.19', 'text' => 'Guide rails fastening and equipment are suitable for the purpose, show no defects, and the guide rails are correctly lubricated (where required) (manufacturer specification)', 'ref' => 'ASME A17.1 Sec. (2.23 (3.23.2), 3.15, 3.23, 3.38, 8.11.3.1.3t)'],
                    ['number' => '1.3.20', 'text' => 'Governor rope condition and that it is fitted with a tag', 'ref' => 'ASME A17.1 Sec. (2.18.5, 3.17.1, 8.6.4.2, 8.7.2.19, 8.11.2.1.3, 8.11.3.1.3w)'],
                    ['number' => '1.3.21', 'text' => 'Condition of governor releasing carrier and that it is set to require a tension in the governor rope to pull the rope from the carrier of not more than 60% of the pull-through tension developed by the governor. The means to regulate this force shall be mechanical and shall be sealed', 'ref' => 'ASME A17.1 Sec. (2.17.15, 3.17.1, 8.11.3.1.3y, 8.11.3.4)'],
                    ['number' => '1.3.22', 'text' => 'Wire rope fastening and hitch plate are secured using bolts or rivets', 'ref' => 'ASME A17.1 Sec. (2.9.3.3, 2.15.13, 2.20, 3.18.1.2, 8.6.3, 8.11.3.1.3x)'],
                    ['number' => '1.3.23', 'text' => 'Specification and suitability of the suspension rope and its fastenings is acceptable (in the case of a new rope the sheave material shall be assessed as suitable or not)', 'ref' => 'ASME A17.1 Sec. (2.20, 8.2.7, 8.6.2.5, 8.7.2.21, 8.7.3.25, 8.11.2.1.3cc, 8.11.3.1.3y)'],
                    ['number' => '1.3.24', 'text' => 'Speed test in both directions is in accordance with manufacturers specifications', 'ref' => 'ASME A17.1 Sec. (2.17.16, 3.4, 8.10.3.2.3cc, 8.11.3.1.3h)'],
                    ['number' => '1.3.25', 'text' => 'Slack rope device (roped-hydraulic elevators installed under A17.1b-1989 and later editions) does cause the electric power to be removed from the hydraulic machine pump motor and control valves should a rope become slack', 'ref' => 'ASME A17.1 Sec. (3.18.1.2, 3.26.4, 8.11.3.1.3z)'],
                    ['number' => '1.3.26', 'text' => 'Travelling sheave (roped-hydraulic elevators installed under A17.1b-1989 and later editions) is attached using suitable fastenings (the loading being the resultant of the maximum tensions in the ropes leading from the sheave with the elevator at rest and with rated load in the car)', 'ref' => 'ASME A17.1 Sec. (2.20, 2.24.2, 2.24.3, 2.24.5, 3.18.1.2, 3.23.2, 8.7.3.25, 8.11.3.1.3aa)'],
                    ['number' => '1.3.27', 'text' => 'Counterweight, counterweight buffers and safeties are in compliance with design requirements', 'ref' => 'ASME A17.1 Sec. (3.4.6, 3.17.2, 3.22.2, 8.2.3)'],
                ]
            ],
            [
                'number' => '1.4',
                'title' => 'OUTSIDE HOIST WAY',
                'ref' => '',
                'subitems' => [
                    ['number' => '1.4.1', 'text' => 'Car platform guard plates comply with material specification (steel) and thickness (not less than 1.5 mm)', 'ref' => 'ASME A17.1 Sec. (3.15, 8.11.3.1.4a)'],
                    ['number' => '1.4.2', 'text' => 'Hoist way doors operate correctly', 'ref' => 'ASME A17.1 Sec. (2.11 (3.11), 2.12.2.2, 2.12.3.2 (3.12), 3.26.4, 8.10.3.2.3r, 8.11.3.1.4b)'],
                    ['number' => '1.4.3', 'text' => 'Car vision panel (if fitted) is 0.1sq.m. (Max) and either wire-glass or laminated, and in the case of glass doors be laminated, safety glass or safety plastic, with not less than 60% of the total visible door panel surface area as glass', 'ref' => 'ASME A17.1 Sec. (2.11.7 (3.11), 8.11.3.1.4c)'],
                    ['number' => '1.4.4', 'text' => 'Hoist way door locking devices are operational (interlocks)', 'ref' => 'ASME A17.1 Sec. (2.12 (3.12), 8.11.3.1.4d)'],
                    ['number' => '1.4.5', 'text' => 'Access to hoist way (at top or bottom landing) is by use of an access switch adjacent to the entrance', 'ref' => 'ASME A17.1 Sec. (2.12.6, 2.12.7 (3.12), 8.11.3.1.4e)'],
                    ['number' => '1.4.6', 'text' => 'Hoist way doors are power closing', 'ref' => 'ASME A17.1 Sec. (2.13.3, 2.13.6 (3.13), 8.11.3.1.4f)'],
                    ['number' => '1.4.7', 'text' => 'Sequence of operation of hoist way doors is correct', 'ref' => 'ASME A17.1 Sec. (2.13.3.4 (3.13), 2.13.6, 8.11.3.1.4g)'],
                    ['number' => '1.4.8', 'text' => 'Verify hoist way enclosure fire resistance (or non-fire resistance, depending on building code) (other general requirements such as floor strength and location depend on the code - check specification)', 'ref' => 'ASME A17.1 Sec. (2.1.1, 2.1.4, 2.1.5 (3.1), 8.11.3.1.4h)'],
                    ['number' => '1.4.9', 'text' => 'Elevator parking devices are operable', 'ref' => 'ASME A17.1 Sec. (8.11.3.1.4i)'],
                    ['number' => '1.4.10', 'text' => 'Emergency doors in blind hoist way are on every third floor, not more than 11m from sill to sill with a clear opening of 700mm x 2030mm (at least), and doors are self-closing and self-locking and marked "Danger Elevator Hoist way" in 50mm letters (an open or unlocked door removes power from the elevator motor)', 'ref' => 'ASME A17.1 Sec. (2.11.1.1, 2.11.1.2)'],
                    ['number' => '1.4.11', 'text' => 'Standby power selection switch is marked "Elevator Emergency Power" and key operated under a locked cover', 'ref' => 'ASME A17.1 Sec. (2.16.8 (3.16), 2.27.2, 2.27.8 (3.27), 8.11.2.2.7, 8.11.3.1.4k, 8.11.3.2.3)'],
                ]
            ],
            [
                'number' => '1.5',
                'title' => 'ELEVATOR PIT',
                'ref' => '',
                'subitems' => [
                    ['number' => '1.5.1', 'text' => 'Pit access, lighting and stop switch meet design requirements', 'ref' => 'ASME A17.1 Sec. (2.8 (3.8), 3.6, 3.26.4, 8.6.4.7, 8.11.3.1.5a)'],
                    ['number' => '1.5.2', 'text' => 'Verify bottom clearance as 600mm; run by clearance as 75mm (min.) 150mm (max., speed dependent); and minimum refuge space as 600 x1200 x600 mm or 450 x 900 x 1070 mm', 'ref' => 'ASME A17.1 Sec. (3.4, 3.18.3.3, 8.10.3.2.5c, 8.11.3.1.5b)'],
                    ['number' => '1.5.3', 'text' => 'Normal terminal stopping devices operate correctly to slow down and operate the car correctly at or near top and bottom terminal landings (up to rated load and speed)', 'ref' => 'ASME A17.1 Sec. (3.25.1, 8.11.2.2.5 (8.11.3.2.3), 8.11.3.1.5e)'],
                    ['number' => '1.5.4', 'text' => 'Travel cables are undamaged and serviceable', 'ref' => 'ASME A17.1 Sec. (2.8.2 (3.8), 8.11.3.1.5f)'],
                    ['number' => '1.5.5', 'text' => 'Governor-rope tension device is working satisfactorily', 'ref' => 'ASME A17.1 Sec. (2.18.7, 3.17.1, 8.6.1.6.2, 8.11.3.1.5k)'],
                    ['number' => '1.5.6', 'text' => 'Car frame and platform meet requirements as per manufacturers specification', 'ref' => 'ASME A17.1 Sec. (3.15, 2.18.2.3, 3.28, 8.11.3.1.5g)'],
                    ['number' => '1.5.7', 'text' => 'Car safeties guarding members are in place and secure - including roped-hydraulic elevators installed under A17.1b-1989 and later editions (where applicable)', 'ref' => 'ASME A17.1 Sec. (2.17, 3.17.1, 8.2.6, 8.11.3.1.5j)'],
                    ['number' => '1.5.8', 'text' => 'Plunger and cylinder comply with design requirements (Plunger shall not strike the safety bulkhead of the cylinder when the car is resting on its fully compressed buffer)', 'ref' => 'ASME A17.1 Sec. (3.18, 8.6.5.1, 8.6.5.2, 8.6.5.5, 8.6.5.6, 8.11.3.1.5c)'],
                    ['number' => '1.5.9', 'text' => 'Plunger stops are provided to prevent the plunger from travelling beyond the limits of the cylinder in the up direction at maximum speed and full load pressure', 'ref' => 'ASME A17.1 Sec. (3.18, 8.6.5.1, 8.6.5.2, 8.6.5.5, 8.6.5.6, 8.11.3.1.5c)'],
                    ['number' => '1.5.10', 'text' => 'Car buffers are in place where required and undamaged', 'ref' => 'ASME A17.1 Sec. (3.22.1, 3.26.4, 8.2.3.2, 8.6.4.4, 8.11.3.1.5d)'],
                    ['number' => '1.5.11', 'text' => 'Guiding members are in position, securely bracketed, and meet design requirements', 'ref' => 'ASME A17.1 Sec. (3.23, 3.28, 8.6.4.3, 8.11.3.1.5h)'],
                    ['number' => '1.5.12', 'text' => 'Oil supply piping meets design requirements (as per manufacturer) and is leak-proof and secure', 'ref' => 'ASME A17.1 Sec. (2.24, 8.10.3.2.2r, 8.11.3.1.5i)'],
                ]
            ],
            [
                'number' => '1.6',
                'title' => 'FIREFIGHTER\'S SERVICE',
                'ref' => '',
                'subitems' => [
                    ['number' => '1.6.1', 'text' => 'Verify / check operation of elevators under fire and other emergency conditions (A17.1b-1973 through A17.1b-1980)', 'ref' => 'ASME A17.1 Sec. (2.13.3.4, 2.13.5, 8.6.10.1, 8.11.2.1.4l, 8.11.2.2.6)'],
                    ['number' => '1.6.2', 'text' => 'Verify / check operation of elevators under fire and other emergency conditions (A17.1-1981 through A17.1b-1983)', 'ref' => 'ASME A17.1 Sec. (2.13.3.4, 2.13.5, 8.6.10.1, 8.11.2.1.4l, 8.11.2.2.6)'],
                    ['number' => '1.6.3', 'text' => 'Verify / check operation of elevators under fire and other emergency conditions (A17.1-1984 through A17.1a-1988 and A17.3)', 'ref' => 'ASME A17.1 Sec. (2.13.3.4, 2.13.5, 8.6.10.1, 8.11.2.1.4l, 8.11.2.2.6)'],
                    ['number' => '1.6.4', 'text' => 'Verify / check operation of elevators under fire and other emergency conditions (A17.1b-1989 and later edition)', 'ref' => 'ASME A17.1 Sec. (2.13.3.4, 2.13.5, 8.6.10.1, 8.11.2.1.4l, 8.11.2.2.6)'],
                ]
            ],
        ],
    
    [
        'number' => '2',
        'title' => 'ELECTRIC ELEVATOR',
        'ref' => 'ASME A17.1',
        'items' => [
            [
                'number' => '2.1',
                'title' => 'INSIDE OF CAR',
                'ref' => '',
                'subitems' => [
                    ['number' => '2.1.1', 'text' => 'Door reopening device is operating correctly', 'ref' => 'ASME A17.1 Sec. (8.11.2.1.1a)'],
                    ['number' => '2.1.2', 'text' => 'Emergency stop switches are not provided on passenger elevators but are provided on freight elevators, in the car and in or adjacent to each car operating panel', 'ref' => 'ASME A17.1 Sec. (2.26.2.5, 2.26.2.21, 8.11.2.1.1b)'],
                    ['number' => '2.1.3', 'text' => 'All operating control devices are of the enclosed electric type', 'ref' => 'ASME A17.1 Sec. (2.26.1.1, 2.26.1.6, 8.11.2.1.1c)'],
                    ['number' => '2.1.4', 'text' => 'Sills are of the correct type and are of sufficient strength and clearance with adjoining car platform or hoist way sill (min. clearance 13mm)', 'ref' => 'ASME A17.1 Sec. (2.5.1.4, 2.11.10.3, 2.11.11.1, 2.11.13.1, 2.15.16, 8.11.2.1.1d)'],
                    ['number' => '2.1.5', 'text' => 'Car has minimum of two lamps (min. of 50 lux for passenger and 25 lux for freight elevators) (Passenger elevators shall have auxiliary lighting which automatically turns on if normal power fails)', 'ref' => 'ASME A17.1 Sec. (2.14.7, 8.11.2.1.1e)'],
                    ['number' => '2.1.6', 'text' => 'Passenger elevators are equipped with auxiliary lighting which automatically turns on if normal power fails', 'ref' => 'ASME A17.1 Sec. (2.14.7, 8.11.2.1.1e)'],
                    ['number' => '2.1.7', 'text' => 'Car emergency communication signal to authorized and emergency personnel is available and working', 'ref' => 'ASME A17.1 Sec. (2.27.1, 8.11.2.1.1f)'],
                    ['number' => '2.1.8', 'text' => 'Car door or gate has electric contacts or interlocks (where required) to prevent operation of the driving machine when the door or gate is open', 'ref' => 'ASME A17.1 Sec. (2.13.2.1, 2.14.4, 2.14.5, 2.14.6, 2.26.2.15, 8.11.2.1.1g)'],
                    ['number' => '2.1.9', 'text' => 'The force necessary to prevent door closing does not exceed 30ft.lb', 'ref' => 'ASME A17.1 Sec. (2.13.4.2.3, 8.11.2.1.1h, 8.11.2.2.8)'],
                    ['number' => '2.1.10', 'text' => 'Power closing of doors or gates (vertically sliding) is preceded by a warning bell at least 5 seconds prior to door or gate movement and continues until substantial closure (Closure using a switch or button in the car omits the 5 second time interval)', 'ref' => 'ASME A17.1 Sec. (2.13.3, 8.11.2.1.1i)'],
                    ['number' => '2.1.11', 'text' => 'Power opening of doors or gates only occurs when the car is at rest at the landing, or in the landing zone', 'ref' => 'ASME A17.1 Sec. (2.26.1.6, 2.26.9, 2.26.9.3, 8.11.2.1.1j, 8.11.2.3.7, 8.11.2.3.8, 8.11.2.3.9)'],
                    ['number' => '2.1.12', 'text' => 'Car vision panel (if fitted) is 0.1sq.m. (Max) and either wire-glass or laminated, and in the case of glass doors be laminated, safety glass or safety plastic, with not less than 60% of the total visible door panel surface area as glass', 'ref' => 'ASME A17.1 Sec. (2.14.2.5, 2.14.5.8, 8.11.2.1.1k)'],
                    ['number' => '2.1.13', 'text' => 'Laminated glass vision panel is a safety glass or safety plastic, with not less than 60% of the total visible door panel surface area as glass', 'ref' => 'ASME A17.1 Sec. (2.14.2.5, 2.14.5.8, 8.11.2.1.1k)'],
                    ['number' => '2.1.14', 'text' => 'Car enclosure is in compliance with the required equipment (specification)', 'ref' => 'ASME A17.1 Sec. (2.14, 2.16.2.2, 2.16.4, 2.16.5, 2.29.1, 8.3.7, 8.7.2.14, 8.11.2.1.1m)'],
                    ['number' => '2.1.15', 'text' => 'Verify the emergency exit (and cover) is provided in the top of the car (except cars in partially enclosed hoist ways)', 'ref' => 'ASME A17.1 Sec. (2.14.1.5, 2.14.1.10, 8.11.2.1.1m)'],
                    ['number' => '2.1.16', 'text' => 'Ventilation (natural or forced) complies with the various opening and size requirements as well as air change volume per minute (for forced ventilation)', 'ref' => 'ASME A17.1 Sec. (2.14.2.3, 2.14.3.3, 8.11.2.1.1n)'],
                    ['number' => '2.1.17', 'text' => 'Signs and operating device symbols are installed and legible', 'ref' => 'ASME A17.1 Sec. (2.26.12, 8.11.2.1.1o)'],
                    ['number' => '2.1.18', 'text' => 'Rated load, rated load marking and car platform area and data plate are available and legible', 'ref' => 'ASME A17.1 Sec. (2.16, 8.11.2.1.1p)'],
                    ['number' => '2.1.19', 'text' => 'Standby power operation (at least one elevator at a time) with design load in the event of power supply failure (transfer from normal to standby supply is automatic)', 'ref' => 'ASME A17.1 Sec. (2.27.2, 8.11.2.2.7, 8.11.2.3.10)'],
                    ['number' => '2.1.20', 'text' => 'Restricted opening of car or hoist way doors (4" max) is possible outside the unlocking zone', 'ref' => 'ASME A17.1 Sec. (2.12.5, 8.11.2.1.1r, 8.11.2.2.3)'],
                    ['number' => '2.1.21', 'text' => 'Car ride is smooth in acceleration and deceleration throughout its travel', 'ref' => 'ASME A17.1 Sec. (2.15.2, 2.23, 8.11.2.1.1s)'],
                ]
            ],
            [
                'number' => '2.2',
                'title' => 'MACHINE ROOM',
                'ref' => '',
                'subitems' => [
                    ['number' => '2.2.1', 'text' => 'The access to the machine space is in conformance with the type of access, location, and combustibility allowed', 'ref' => 'ASME A17.1 Sec. (2.7.1.1, 2.7.3.1, 2.7.3.2, 2.7.3.3, 2.7.3.4, 8.11.2.1.2a)'],
                    ['number' => '2.2.2', 'text' => 'Minimum headroom clearance is either 84", 53", 42", or 35" depending on type and location of machine room / hoist way', 'ref' => 'ASME A17.1 Sec. (2.7.4, 8.11.2.1.2c)'],
                    ['number' => '2.2.3', 'text' => 'Electric lighting in the machine room is not less than 200 lux at floor level and the control switch is at the lock - jamb side of the access door', 'ref' => 'ASME A17.1 Sec. (2.7.5.1, 8.11.2.1.2c)'],
                ]
            ],
        ]
    ]
];

$index = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inspection Checklist for Elevators and Escalators</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .section {
            background-color: #c0d6e8 !important;
            font-weight: bold;
            text-align: center;
        }
        body { font-size: 11px; }
        .checkbox-cell { text-align: center; }
        @media print {
            body * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .no-print { display: none !important; }
            .keep-together { page-break-inside: avoid; break-inside: avoid; }
        }
    </style>
</head>
<body>
<div class="container-fluid" style="max-width: 1000px;">
    <table class="w-100">
        <tr>
            <td rowspan="4" class="text-center">
                <img src="../../logo.png" alt="CIMS Logo" width="80" />
            </td>
            <td colspan="3" class="font-weight-bold">
                CRANE INSPECTION & MAINTENANCE SERVICES<br>
                A DIVISION OF AL-KHOBAR GATE INTERNATIONAL TRADING EST.
            </td>
        </tr>
        <tr>
            <td colspan="3" class="font-weight-bold">
                INSPECTION CHECKLIST FOR ELEVATORS AND ESCALATORS
            </td>
        </tr>
        <tr>
            <td>FRM.0601-1.2</td>
            <td>Revision 02</td>
            <td>Issue Date: 30/SEP/2020</td>
        </tr>
        <tr>
            <td class="text-left"><strong>Prepared By</strong><br>Operations Manager</td>
            <td class="text-left"><strong>Reviewed & Approved By</strong><br>Managing Director</td>
            <td><img src="../../../code.png" width="70px" height="70px" alt="" /></td>
        </tr>
    </table>

    <h5 class="mt-3">ELEVATORS AND ESCALATORS - ASME A17.1</h5>

    <table class="table table-bordered mt-3">
        <tr>
            <th style="width: 25%; background-color: #c0d6e8;">REPORT NO</th>
            <td style="width: 25%;"><strong><?php echo $row['report_no']; ?></strong></td>
            <th style="width: 25%; background-color: #c0d6e8;">INSPECTION DATE</th>
            <td style="width: 25%;"><strong><?php echo date('F j, Y', strtotime($row['inspection_date'])); ?></strong></td>
        </tr>
        <tr>
            <th style="background-color: #c0d6e8;">CLIENT'S NAME</th>
            <td><strong><?php echo $row['client_name']; ?></strong></td>
            <th style="background-color: #c0d6e8;">INSPECTED BY</th>
            <td><strong><?php echo $row['inspected_by']; ?></strong></td>
        </tr>
        <tr>
            <th style="background-color: #c0d6e8;">LOCATION</th>
            <td><strong><?php echo $row['location']; ?></strong></td>
            <th style="background-color: #c0d6e8;">STICKER NO.</th>
            <td><strong><?php echo $row['sticker_no']; ?></strong></td>
        </tr>
        <tr>
            <th style="background-color: #c0d6e8;">EQUIPMENT NO</th>
            <td><strong><?php echo $row['equipment_no']; ?></strong></td>
            <th style="background-color: #c0d6e8;">EQUIP. SERIAL NO.:</th>
            <td><strong><?php echo $row['crane_serial_no']; ?></strong></td>
        </tr>
        <tr>
            <th style="background-color: #c0d6e8;">EQUIPMENT TYPE</th>
            <td><strong><?php echo $row['equipmenttype']; ?></strong></td>
            <th style="background-color: #c0d6e8;">CAPACITY (SWL)</th>
            <td><strong><?php echo $row['capacity_swl']; ?></strong></td>
        </tr>
    </table>

    <table class="table table-bordered table-sm">
        <thead class="thead-dark">
            <tr>
                <th style="text-align: center; width: 8%;">S.N</th>
                <th style="text-align: center; width: 40%;">ACCEPTANCE CRITERIA</th>
                <th style="text-align: center; width: 25%;">REFERENCE</th>
                <th style="text-align: center; width: 9%;" colspan="3">RESULT</th>
                <th style="text-align: center; width: 18%;">REMARKS</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($sections as $section) {
                // Main section header
                echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($section['number'] . ' ' . $section['title']) . " - " . htmlspecialchars($section['ref']) . "</td></tr>";
                
                foreach ($section['items'] as $subsection) {
                    // Subsection header
                    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($subsection['number'] . ' ' . $subsection['title']) . "</td></tr>";
                    
                    // Items under subsection
                    foreach ($subsection['subitems'] as $item) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($item['number']) . "</strong></td>";
                        echo "<td><strong>" . htmlspecialchars($item['text']) . "</strong></td>";
                        echo "<td style='font-size: 9px;'><strong>" . htmlspecialchars($item['ref']) . "</strong></td>";
                        echo "<td class='checkbox-cell'>";
                        $resultValue = isset($selected_results[$index]) ? $selected_results[$index] : '';
                        echo ($resultValue == 'PASS') ? '✓ PASS' : '';
                        echo "</td>";
                        echo "<td class='checkbox-cell'>";
                        echo ($resultValue == 'FAIL') ? '✓ FAIL' : '';
                        echo "</td>";
                        echo "<td class='checkbox-cell'>";
                        echo ($resultValue == 'NA') ? '✓ NA' : '';
                        echo "</td>";
                        echo "<td>" . htmlspecialchars(isset($chek_remark[$index]) ? $chek_remark[$index] : '') . "</td>";
                        echo "</tr>";
                        $index++;
                    }
                }
            }
            ?>
        </tbody>
    </table>

    <div class="keep-together">
    <table class="table table-bordered mt-4">
        <tr>
            <th colspan="2" style="background-color: #c0d6e8;">REMARKS / RECOMMENDATIONS</th>
        </tr>
        <tr>
            <td colspan="2" style="height: 80px; vertical-align: top;">
                <textarea style="width: 100%; height: 100%; border: none;" readonly><?php echo isset($_GET['inspection_remarks']) ? htmlspecialchars($_GET['inspection_remarks']) : ''; ?></textarea>
            </td>
        </tr>
    </table>

    <table class="table table-bordered mt-4">
        <tr>
            <th style="width: 50%; background-color: #c0d6e8;">INSPECTOR / TECHNICIAN</th>
            <th style="width: 50%; background-color: #c0d6e8;">CLIENT / OWNER REPRESENTATIVE</th>
        </tr>
        <tr>
            <td style="height: 60px; padding-bottom: 0;">
                <div style="height: 35px;"></div>
                <div style="border-top: 1px solid black; text-align: center; font-size: 9px;">Signature</div>
            </td>
            <td style="height: 60px; padding-bottom: 0;">
                <div style="height: 35px;"></div>
                <div style="border-top: 1px solid black; text-align: center; font-size: 9px;">Signature</div>
            </td>
        </tr>
        <tr>
            <td style="height: 30px;">
                <div style="font-size: 9px;">Name: ____________________</div>
            </td>
            <td style="height: 30px;">
                <div style="font-size: 9px;">Name: ____________________</div>
            </td>
        </tr>
        <tr>
            <td style="height: 20px;">
                <div style="font-size: 9px;">Date: ____________________</div>
            </td>
            <td style="height: 20px;">
                <div style="font-size: 9px;">Date: ____________________</div>
            </td>
        </tr>
    </table>
    </div>
</div>
</body>
</html>
