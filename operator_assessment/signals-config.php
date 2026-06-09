<?php
/**
 * Hand Signals Configuration
 * Defines all 18 crane operator hand signals for practical testing
 */

// Hand signals data - 18 standard crane signals
$hand_signals = [
    1 => [
        'name' => 'Stop',
        'description' => 'Arm extended, palm down, move hand rapidly right and left',
        'image' => 'signal-01.jpg'
    ],
    2 => [
        'name' => 'Emergency Stop',
        'description' => 'Both arms extended, palms down, move hands rapidly right and left',
        'image' => 'signal-02.jpg'
    ],
    3 => [
        'name' => 'Move Slowly',
        'description' => 'One hand placed in front of hand giving signal',
        'image' => 'signal-03.jpg'
    ],
    4 => [
        'name' => 'Swing',
        'description' => 'Arm extended, point with finger in direction of swing',
        'image' => 'signal-04.jpg'
    ],
    5 => [
        'name' => 'Retract Boom / Telescope In',
        'description' => 'Both fists in front of body with thumbs pointing toward each other',
        'image' => 'signal-05.jpg'
    ],
    6 => [
        'name' => 'Hoist / Raise Load',
        'description' => 'Arm extended upward, forefinger pointing up, move hand in small horizontal circle',
        'image' => 'signal-06.jpg' // PLACEHOLDER - Upload remaining images
    ],
    7 => [
        'name' => 'Lower Load',
        'description' => 'Arm extended downward, forefinger pointing down, move hand in small horizontal circle',
        'image' => 'signal-07.jpg' // PLACEHOLDER
    ],
    8 => [
        'name' => 'Raise Boom',
        'description' => 'Arm extended, fingers closed, thumb pointing upward',
        'image' => 'signal-08.jpg' // PLACEHOLDER
    ],
    9 => [
        'name' => 'Lower Boom',
        'description' => 'Arm extended, fingers closed, thumb pointing downward',
        'image' => 'signal-09.jpg' // PLACEHOLDER
    ],
    10 => [
        'name' => 'Extend Boom / Telescope Out',
        'description' => 'Both fists in front of body with thumbs pointing outward',
        'image' => 'signal-10.jpg' // PLACEHOLDER
    ],
    11 => [
        'name' => 'Travel / Move Forward',
        'description' => 'Arm extended forward, hand open and slightly raised, make pushing motion',
        'image' => 'signal-11.jpg' // PLACEHOLDER
    ],
    12 => [
        'name' => 'Travel / Move Backward',
        'description' => 'Arm extended backward, hand open and slightly raised, make pushing motion',
        'image' => 'signal-12.jpg' // PLACEHOLDER
    ],
    13 => [
        'name' => 'Dog Everything',
        'description' => 'Clasp hands in front of body',
        'image' => 'signal-13.jpg' // PLACEHOLDER
    ],
    14 => [
        'name' => 'Travel Both Tracks',
        'description' => 'Use both fists in front of body, making circular motion',
        'image' => 'signal-14.jpg' // PLACEHOLDER
    ],
    15 => [
        'name' => 'Travel One Track',
        'description' => 'Raise one fist, make circular motion',
        'image' => 'signal-15.jpg' // PLACEHOLDER
    ],
    16 => [
        'name' => 'Extend Outriggers',
        'description' => 'Both arms extended to sides, palms down',
        'image' => 'signal-16.jpg' // PLACEHOLDER
    ],
    17 => [
        'name' => 'Retract Outriggers',
        'description' => 'Both arms extended to sides, palms up',
        'image' => 'signal-17.jpg' // PLACEHOLDER
    ],
    18 => [
        'name' => 'Use Main Hoist',
        'description' => 'Tap fist on head, then use regular signals',
        'image' => 'signal-18.jpg' // PLACEHOLDER
    ]
];

// Test settings
$signals_settings = [
    'total_signals' => 18,
    'passing_percentage' => 80, // Need 80% to pass
    'minimum_pass' => 15, // 15 out of 18 signals (83.33%)
    'allow_retake' => true,
    'max_attempts' => 3,
    'randomize_order' => false // Set to true to show signals in random order
];

// Helper function to get signal by number
function getSignal($number) {
    global $hand_signals;
    return isset($hand_signals[$number]) ? $hand_signals[$number] : null;
}

// Helper function to check if image exists
function signalImageExists($signal_number) {
    $image_path = __DIR__ . '/hand-signals/signal-' . str_pad($signal_number, 2, '0', STR_PAD_LEFT) . '.jpg';
    return file_exists($image_path);
}
?>
