<?php
/**
 * Exam Configuration
 * Contains all questions and correct answers for the operator written exam
 */

// Correct answers for all 20 questions
$correct_answers = [
    1 => 'c',  // 5 tons
    2 => 'c',  // the parts of line used
    3 => 'c',  // the angle of the boom
    4 => 'a',  // raise the boom
    5 => 'a',  // the tow boom angle
    6 => 'd',  // when the hook block is stopped before hitting the boom tip
    7 => 'c',  // 132 lb.
    8 => 'b',  // 11,000 kg
    9 => 'b',  // 21.32 ft
    10 => 'b', // 4.5 m
    11 => 'c', // 7.4 kg
    12 => 'a', // 3 m
    13 => 'c', // 18.6 m
    14 => 'b', // 30 degree
    15 => 'b', // 4.5 m
    16 => 'b', // 21 m
    17 => 'b', // 13.8 tons
    18 => 'c', // stay in the cab and wait for the rescue
    19 => 'a', // Load moment indicator
    20 => 'c'  // Rated Capacity Limiter
];

// All questions with their options
$exam_questions = [
    1 => [
        'question' => 'What is the maximum rated capacity of the crane whose SWL is 5 tons?',
        'options' => [
            'a' => '10 tons',
            'b' => '8 tons',
            'c' => '5 tons',
            'd' => '2 tons'
        ]
    ],
    2 => [
        'question' => 'What information does the LMI gives the operator?',
        'options' => [
            'a' => 'the size of the hoist rope',
            'b' => 'the sling capacity',
            'c' => 'the parts of line used',
            'd' => 'the size of the hook block'
        ]
    ],
    3 => [
        'question' => 'What information does the LMI gives the operator?',
        'options' => [
            'a' => 'the angle of the load',
            'b' => 'the angle of the tag line',
            'c' => 'the angle of the boom',
            'd' => 'the angle of the slings'
        ]
    ],
    4 => [
        'question' => 'What corrective action must the operator take when the LMI warning indicator sounds?',
        'options' => [
            'a' => 'raise the boom',
            'b' => 'switch-off the LMI',
            'c' => 'lower the boom',
            'd' => 'extend the boom'
        ]
    ],
    5 => [
        'question' => 'What information must be entered into the LMI before any lift is undertaken?',
        'options' => [
            'a' => 'the tow boom angle',
            'b' => 'the sling size',
            'c' => 'the weight of the load',
            'd' => 'the operator\'s name'
        ]
    ],
    6 => [
        'question' => 'What is the anti-two block system working correctly?',
        'options' => [
            'a' => 'when there is more than one part line',
            'b' => 'when the headlights flash on & off',
            'c' => 'when there is only one part line',
            'd' => 'when the hook block is stopped before hitting the boom tip'
        ]
    ],
    7 => [
        'question' => 'How many lbs. is 60 kg? (multiple choices)',
        'options' => [
            'a' => '110 lb.',
            'b' => '120 lb.',
            'c' => '132 lb.',
            'd' => '142 lb.'
        ]
    ],
    8 => [
        'question' => 'How many kg is 22,000 lb.? (multiple choices)',
        'options' => [
            'a' => '10,000 kg',
            'b' => '11,000 kg',
            'c' => '12,000 kg',
            'd' => '9,000 kg'
        ]
    ],
    9 => [
        'question' => 'How many feet is 6.5 meters?',
        'options' => [
            'a' => '21.125 ft.',
            'b' => '21.32 ft.',
            'c' => '20.8 ft.',
            'd' => '21.45 ft.'
        ]
    ],
    10 => [
        'question' => 'Using the LRT 230E load chart, what is the radius?',
        'context' => 'Boom Length = 10 m',
        'options' => [
            'a' => '4 m',
            'b' => '4.5 m',
            'c' => '5 m',
            'd' => '5.5 m'
        ]
    ],
    11 => [
        'question' => 'Using the LRT 230E load chart what is the max, load?',
        'context' => 'Radius = 10 m, Boom Length = 18.6 m',
        'options' => [
            'a' => '7,500 kg',
            'b' => '74,000 kg',
            'c' => '7.4 kg',
            'd' => '0.74 ton'
        ]
    ],
    12 => [
        'question' => 'Use the TR-300E range diagram and a hoist clearance of 10 m.',
        'context' => 'Boom angle = 40 degrees, Boom length = 15.6 m',
        'sub_question' => 'What is the hook elevation?',
        'options' => [
            'a' => '3 m',
            'b' => '6 m',
            'c' => '8 m',
            'd' => '10 m'
        ]
    ],
    13 => [
        'question' => 'Use the LRT 230E range diagram.',
        'context' => 'Radius = 15 m, Boom angle = 27 degree',
        'sub_question' => 'What is the boom length?',
        'options' => [
            'a' => '11.9 m',
            'b' => '15.2 m',
            'c' => '18.6 m',
            'd' => '21.9 m'
        ]
    ],
    14 => [
        'question' => 'Use the LRT 230E range diagram.',
        'context' => 'Boom length = 11.9 m, Radius = 9 m',
        'sub_question' => 'What is the boom angle?',
        'options' => [
            'a' => '28 degree',
            'b' => '30 degree',
            'c' => '32 degree',
            'd' => '33 degree'
        ]
    ],
    15 => [
        'question' => 'Use the LRT 230E range diagram.',
        'context' => 'Boom length = 9.1 m, Boom angle = 50 degrees',
        'sub_question' => 'What is the radius?',
        'options' => [
            'a' => '3 m',
            'b' => '4.5 m',
            'c' => '5 m',
            'd' => '6 m'
        ]
    ],
    16 => [
        'question' => 'Use the TR-300E range diagram.',
        'context' => 'Boom angle = 40 degrees, Boom length = 28.6 m',
        'sub_question' => 'What is the boom tip height?',
        'options' => [
            'a' => '20 m',
            'b' => '21 m',
            'c' => '22 m',
            'd' => '23 m'
        ]
    ],
    17 => [
        'question' => 'Use the TR 300E load chart.',
        'context' => 'Boom length = 15.6 m, Radius = 6.5 m',
        'sub_question' => 'What is the Maximum capacity (load)?',
        'options' => [
            'a' => '12.9 tons',
            'b' => '13.8 tons',
            'c' => '14.0 tons',
            'd' => '14.7 tons'
        ]
    ],
    18 => [
        'question' => 'When the crane comes within the range of high tension power lines. What shall the Operator do\'s?',
        'options' => [
            'a' => 'jump off the cabin',
            'b' => 'move the crane backwards',
            'c' => 'stay in the cab and wait for the rescue',
            'd' => 'move forwards'
        ]
    ],
    19 => [
        'question' => 'What is meant by LMI?',
        'options' => [
            'a' => 'Load moment indicator',
            'b' => 'Load measuring indicator',
            'c' => 'Length moment indicator',
            'd' => 'Length measuring indicator'
        ]
    ],
    20 => [
        'question' => 'What is the acronym of RCL?',
        'options' => [
            'a' => 'Rated Car Limited',
            'b' => 'Related Crane Comparison',
            'c' => 'Rated Capacity Limiter',
            'd' => 'Rated Capacity Limited'
        ]
    ]
];

// Exam settings
$exam_settings = [
    'total_questions' => 20,
    'marks_per_question' => 5,
    'total_marks' => 100,
    'passing_marks' => 80,
    'time_limit_minutes' => 30, // Optional: set to 0 for no time limit
    'allow_retake' => true,
    'max_attempts' => 3
];
?>
