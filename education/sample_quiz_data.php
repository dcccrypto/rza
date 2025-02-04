<?php
require_once '../includes/db.php';

// Sample quiz data
$quiz_data = [
    [
        'title' => 'Zoo Animals Quiz',
        'description' => 'Test your knowledge about the amazing animals at RZA!',
        'questions' => [
            [
                'question' => 'How tall can a male giraffe grow?',
                'options' => json_encode([
                    'Up to 12 feet',
                    'Up to 15 feet',
                    'Up to 18 feet',
                    'Up to 20 feet'
                ]),
                'correct_answer' => 'Up to 18 feet'
            ],
            [
                'question' => 'What is a baby kangaroo called?',
                'options' => json_encode([
                    'A kid',
                    'A joey',
                    'A cub',
                    'A pup'
                ]),
                'correct_answer' => 'A joey'
            ],
            [
                'question' => 'How many hours per day do koalas sleep?',
                'options' => json_encode([
                    '8-10 hours',
                    '12-14 hours',
                    '16-18 hours',
                    '20-22 hours'
                ]),
                'correct_answer' => '20-22 hours'
            ],
            [
                'question' => 'Which big cat is the fastest runner?',
                'options' => json_encode([
                    'Lion',
                    'Tiger',
                    'Leopard',
                    'Cheetah'
                ]),
                'correct_answer' => 'Cheetah'
            ],
            [
                'question' => 'What color is a flamingo when it is born?',
                'options' => json_encode([
                    'Pink',
                    'White',
                    'Grey',
                    'Orange'
                ]),
                'correct_answer' => 'Grey'
            ]
        ]
    ],
    [
        'title' => 'Wildlife Conservation Challenge',
        'description' => 'Learn about protecting endangered species and their habitats.',
        'questions' => [
            [
                'question' => 'What is the main cause of species extinction worldwide?',
                'options' => json_encode([
                    'Habitat loss',
                    'Climate change',
                    'Pollution',
                    'Hunting'
                ]),
                'correct_answer' => 'Habitat loss'
            ],
            [
                'question' => 'Which of these animals is NOT currently endangered?',
                'options' => json_encode([
                    'Giant Panda',
                    'American Bison',
                    'Black Rhino',
                    'Mountain Gorilla'
                ]),
                'correct_answer' => 'American Bison'
            ],
            [
                'question' => 'What is a "biodiversity hotspot"?',
                'options' => json_encode([
                    'A region with many endangered species',
                    'An area with unique plant and animal species',
                    'A location with extreme temperatures',
                    'A place where animals gather to feed'
                ]),
                'correct_answer' => 'An area with unique plant and animal species'
            ],
            [
                'question' => 'Which conservation status indicates the highest risk of extinction?',
                'options' => json_encode([
                    'Vulnerable',
                    'Endangered',
                    'Critically Endangered',
                    'Near Threatened'
                ]),
                'correct_answer' => 'Critically Endangered'
            ],
            [
                'question' => 'What is the purpose of a wildlife corridor?',
                'options' => json_encode([
                    'To count animal populations',
                    'To connect separated habitats',
                    'To protect animals from predators',
                    'To create new wildlife reserves'
                ]),
                'correct_answer' => 'To connect separated habitats'
            ]
        ]
    ],
    [
        'title' => 'Animal Behavior Expert',
        'description' => 'Discover fascinating facts about animal behavior and adaptations!',
        'questions' => [
            [
                'question' => 'Which sense is most developed in elephants?',
                'options' => json_encode([
                    'Sight',
                    'Hearing',
                    'Smell',
                    'Taste'
                ]),
                'correct_answer' => 'Smell'
            ],
            [
                'question' => 'How do penguins stay warm in Antarctica?',
                'options' => json_encode([
                    'They have internal heating',
                    'They huddle together in groups',
                    'They dig underground burrows',
                    'They constantly swim to stay active'
                ]),
                'correct_answer' => 'They huddle together in groups'
            ],
            [
                'question' => 'What is the purpose of a meerkat\'s dark circles around their eyes?',
                'options' => json_encode([
                    'To look cute',
                    'To reduce glare from the sun',
                    'To recognize family members',
                    'To scare predators'
                ]),
                'correct_answer' => 'To reduce glare from the sun'
            ],
            [
                'question' => 'How do dolphins sleep?',
                'options' => json_encode([
                    'They don\'t sleep at all',
                    'They sleep with one half of their brain at a time',
                    'They sleep floating at the surface',
                    'They sleep at the bottom of the ocean'
                ]),
                'correct_answer' => 'They sleep with one half of their brain at a time'
            ],
            [
                'question' => 'Why do zebras have stripes?',
                'options' => json_encode([
                    'To confuse predators',
                    'To attract mates',
                    'To regulate body temperature',
                    'All of the above'
                ]),
                'correct_answer' => 'All of the above'
            ]
        ]
    ],
    [
        'title' => 'Junior Zookeeper Quiz',
        'description' => 'Learn what it takes to be a zookeeper at RZA!',
        'questions' => [
            [
                'question' => 'What is the first thing a zookeeper does in the morning?',
                'options' => json_encode([
                    'Feed the animals',
                    'Check on all animals',
                    'Clean the enclosures',
                    'Update animal records'
                ]),
                'correct_answer' => 'Check on all animals'
            ],
            [
                'question' => 'Why do zookeepers train animals?',
                'options' => json_encode([
                    'For entertainment',
                    'For medical care and enrichment',
                    'To make them perform tricks',
                    'To make them friendly'
                ]),
                'correct_answer' => 'For medical care and enrichment'
            ],
            [
                'question' => 'What is enrichment in a zoo?',
                'options' => json_encode([
                    'Giving animals extra food',
                    'Activities that stimulate natural behaviors',
                    'Teaching animals new tricks',
                    'Cleaning the enclosures'
                ]),
                'correct_answer' => 'Activities that stimulate natural behaviors'
            ],
            [
                'question' => 'How often do zookeepers typically feed large carnivores?',
                'options' => json_encode([
                    'Every hour',
                    'Three times a day',
                    'Once a day',
                    'Every other day'
                ]),
                'correct_answer' => 'Once a day'
            ],
            [
                'question' => 'What is the most important skill for a zookeeper?',
                'options' => json_encode([
                    'Physical strength',
                    'Animal training',
                    'Observation and attention to detail',
                    'Public speaking'
                ]),
                'correct_answer' => 'Observation and attention to detail'
            ]
        ]
    ]
];

try {
    // Start transaction
    $conn->begin_transaction();

    // Clear existing quiz data
    $conn->query("DELETE FROM quiz_attempts");
    $conn->query("DELETE FROM quiz_questions");
    $conn->query("DELETE FROM quizzes");

    // Insert quiz data
    foreach ($quiz_data as $quiz) {
        $stmt = prepare_stmt("INSERT INTO quizzes (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $quiz['title'], $quiz['description']);
        $stmt->execute();
        $quiz_id = $stmt->insert_id;
        
        // Insert questions
        foreach ($quiz['questions'] as $question) {
            $stmt = prepare_stmt("
                INSERT INTO quiz_questions (quiz_id, question, options, correct_answer) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("isss", 
                $quiz_id,
                $question['question'],
                $question['options'],
                $question['correct_answer']
            );
            $stmt->execute();
        }
    }

    // Commit transaction
    $conn->commit();
    echo "<div class='alert alert-success'>Sample quiz data inserted successfully! <a href='quizzes.php' class='alert-link'>Start a Quiz</a></div>";
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    echo "<div class='alert alert-danger'>Error inserting sample quiz data: " . $e->getMessage() . "</div>";
}
?> 