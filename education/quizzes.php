<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_once '../includes/header.php';

// Require login for quiz attempts
require_login();

// Initialize quiz if not started
if (!isset($_SESSION['quiz_in_progress'])) {
    // Get random quiz
    $stmt = prepare_stmt("SELECT id, title, description FROM quizzes ORDER BY RAND() LIMIT 1");
    $stmt->execute();
    $quiz = $stmt->get_result()->fetch_assoc();
    
    if ($quiz) {
        // Get quiz questions
        $stmt = prepare_stmt("SELECT id, question, options FROM quiz_questions WHERE quiz_id = ? ORDER BY RAND() LIMIT 5");
        $stmt->bind_param("i", $quiz['id']);
        $stmt->execute();
        $questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        if (count($questions) > 0) {
            // Store quiz data in session
            $_SESSION['quiz_in_progress'] = true;
            $_SESSION['quiz_id'] = $quiz['id'];
            $_SESSION['quiz_title'] = $quiz['title'];
            $_SESSION['quiz_questions'] = $questions;
            $_SESSION['current_question'] = 0;
            $_SESSION['quiz_score'] = 0;
            $_SESSION['user_answers'] = [];
        } else {
            set_flash_message("No questions available for this quiz.", "warning");
            header('Location: quizzes.php');
            exit;
        }
    } else {
        set_flash_message("No quizzes available at the moment.", "warning");
        header('Location: quizzes.php');
        exit;
    }
}

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    validate_form();
    
    $current = $_SESSION['current_question'];
    $questions = $_SESSION['quiz_questions'];
    
    if ($current < count($questions)) {
        // Get correct answer for current question
        $stmt = prepare_stmt("SELECT correct_answer FROM quiz_questions WHERE id = ?");
        $stmt->bind_param("i", $questions[$current]['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $correct_answer = $result->fetch_assoc()['correct_answer'];
        
        // Store user's answer and check if correct
        $_SESSION['user_answers'][$current] = [
            'question' => $questions[$current]['question'],
            'user_answer' => $_POST['answer'],
            'correct_answer' => $correct_answer,
            'is_correct' => $_POST['answer'] === $correct_answer
        ];
        
        if ($_POST['answer'] === $correct_answer) {
            $_SESSION['quiz_score']++;
        }
        $_SESSION['current_question']++;
    }
    
    // If quiz is complete, save results
    if ($_SESSION['current_question'] >= count($questions)) {
        // Store values in variables before binding
        $user_id = $_SESSION['user_id'];
        $quiz_id = $_SESSION['quiz_id'];
        $score = $_SESSION['quiz_score'];
        $max_score = count($questions);
        
        $stmt = prepare_stmt("
            INSERT INTO quiz_attempts (user_id, quiz_id, score, max_score) 
            VALUES (?, ?, ?, ?)
        ");
        
        // Bind parameters using variables
        $stmt->bind_param("iiii", $user_id, $quiz_id, $score, $max_score);
        $stmt->execute();
        
        // Keep results for display but mark quiz as complete
        $_SESSION['quiz_complete'] = true;
    }
    
    // Redirect to prevent form resubmission
    header('Location: quizzes.php');
    exit;
}

// Ensure current question doesn't exceed total questions
if (isset($_SESSION['quiz_in_progress']) && !isset($_SESSION['quiz_complete'])) {
    $total_questions = count($_SESSION['quiz_questions']);
    if ($_SESSION['current_question'] >= $total_questions) {
        $_SESSION['quiz_complete'] = true;
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if (!isset($_SESSION['quiz_in_progress'])): ?>
                <!-- No active quiz -->
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center p-5">
                        <h2 class="card-title mb-4">Wildlife Knowledge Quiz</h2>
                        <p class="card-text lead mb-4">Test your knowledge about wildlife and conservation!</p>
                        <a href="quizzes.php" class="btn btn-success btn-lg">Start New Quiz</a>
                    </div>
                </div>
                
                <!-- Previous Attempts -->
                <?php
                $stmt = prepare_stmt("
                    SELECT q.title, qa.score, qa.max_score, qa.attempted_at
                    FROM quiz_attempts qa
                    JOIN quizzes q ON qa.quiz_id = q.id
                    WHERE qa.user_id = ?
                    ORDER BY qa.attempted_at DESC
                    LIMIT 5
                ");
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $attempts = $stmt->get_result();
                
                if ($attempts->num_rows > 0):
                ?>
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-body">
                            <h3 class="card-title h5 mb-3">Your Recent Attempts</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Quiz</th>
                                            <th>Score</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($attempt = $attempts->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($attempt['title']) ?></td>
                                                <td>
                                                    <?= $attempt['score'] ?>/<?= $attempt['max_score'] ?>
                                                    (<?= round(($attempt['score'] / $attempt['max_score']) * 100) ?>%)
                                                </td>
                                                <td><?= date('M d, Y H:i', strtotime($attempt['attempted_at'])) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
            <?php elseif (isset($_SESSION['quiz_complete'])): ?>
                <!-- Quiz Results -->
                <div class="card shadow-sm border-0 animate__animated animate__fadeIn">
                    <div class="card-body p-4">
                        <h2 class="card-title mb-4 text-center"><?= htmlspecialchars($_SESSION['quiz_title']) ?> - Results</h2>
                        
                        <?php
                        $score = $_SESSION['quiz_score'];
                        $total = count($_SESSION['quiz_questions']);
                        $percentage = ($score / $total) * 100;
                        ?>
                        
                        <div class="text-center mb-4">
                            <div class="display-1 mb-3 <?= $percentage >= 80 ? 'text-success' : ($percentage >= 60 ? 'text-info' : 'text-warning') ?>">
                                <?= round($percentage) ?>%
                            </div>
                            <h3 class="h4 mb-3">
                                <?= $score ?> out of <?= $total ?> correct
                            </h3>
                            
                            <?php if ($percentage >= 80): ?>
                                <div class="alert alert-success">
                                    <h4 class="alert-heading"><i class="bi bi-trophy-fill"></i> Wildlife Expert!</h4>
                                    <p class="mb-0">Excellent work! You really know your animals. You'd make a great zookeeper!</p>
                                </div>
                            <?php elseif ($percentage >= 60): ?>
                                <div class="alert alert-info">
                                    <h4 class="alert-heading"><i class="bi bi-star-fill"></i> Good Job!</h4>
                                    <p class="mb-0">You have a solid understanding of wildlife. Keep learning to become an expert!</p>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <h4 class="alert-heading"><i class="bi bi-lightbulb-fill"></i> Keep Learning!</h4>
                                    <p class="mb-0">Don't worry! Every attempt helps you learn more about our amazing animals.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <h4 class="mb-3">Review Your Answers</h4>
                        <div class="accordion mb-4" id="answerReview">
                            <?php foreach ($_SESSION['user_answers'] as $index => $answer): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $answer['is_correct'] ? '' : 'collapsed' ?>" 
                                                type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#question<?= $index ?>">
                                            <i class="bi <?= $answer['is_correct'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?> me-2"></i>
                                            Question <?= $index + 1 ?>
                                        </button>
                                    </h2>
                                    <div id="question<?= $index ?>" 
                                         class="accordion-collapse collapse <?= !$answer['is_correct'] ? 'show' : '' ?>">
                                        <div class="accordion-body">
                                            <p class="fw-bold mb-3"><?= htmlspecialchars($answer['question']) ?></p>
                                            <div class="mb-2">
                                                Your answer: 
                                                <span class="<?= $answer['is_correct'] ? 'text-success' : 'text-danger' ?> fw-bold">
                                                    <?= htmlspecialchars($answer['user_answer']) ?>
                                                </span>
                                            </div>
                                            <?php if (!$answer['is_correct']): ?>
                                                <div class="text-success">
                                                    <i class="bi bi-check-lg"></i>
                                                    Correct answer: <?= htmlspecialchars($answer['correct_answer']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="text-center">
                            <div class="row g-3 justify-content-center">
                                <div class="col-auto">
                                    <form method="POST" action="quizzes.php">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="bi bi-arrow-repeat me-2"></i>Try Another Quiz
                                        </button>
                                    </form>
                                </div>
                                <div class="col-auto">
                                    <a href="../education/resources.php" class="btn btn-outline-success btn-lg">
                                        <i class="bi bi-book me-2"></i>Learn More
                                    </a>
                                </div>
                            </div>
                            
                            <?php if ($percentage >= 80): ?>
                                <div class="mt-4">
                                    <div class="badge bg-success p-2">
                                        <i class="bi bi-award-fill me-2"></i>Achievement Unlocked: Wildlife Expert
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php
                // Clear quiz session data for next attempt
                unset(
                    $_SESSION['quiz_in_progress'],
                    $_SESSION['quiz_id'],
                    $_SESSION['quiz_title'],
                    $_SESSION['quiz_questions'],
                    $_SESSION['current_question'],
                    $_SESSION['quiz_score'],
                    $_SESSION['quiz_complete'],
                    $_SESSION['user_answers']
                );
                ?>
                
            <?php else: ?>
                <!-- Active quiz -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h2 class="card-title mb-4"><?= htmlspecialchars($_SESSION['quiz_title']) ?></h2>
                        
                        <!-- Progress bar -->
                        <div class="progress mb-4" style="height: 10px;">
                            <?php
                            $total_questions = count($_SESSION['quiz_questions']);
                            $progress = ($total_questions > 0) ? ($_SESSION['current_question'] / $total_questions) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?= $progress ?>%"
                                 aria-valuenow="<?= $progress ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        
                        <?php
                        $current = $_SESSION['current_question'];
                        if ($current < count($_SESSION['quiz_questions'])):
                            $question = $_SESSION['quiz_questions'][$current];
                            $options = json_decode($question['options'], true);
                            if ($options):
                        ?>
                            
                            <form method="POST" action="" class="quiz-form">
                                <?= csrf_field() ?>
                                
                                <h4 class="mb-4">
                                    Question <?= $current + 1 ?> of <?= count($_SESSION['quiz_questions']) ?>
                                </h4>
                                
                                <p class="lead mb-4"><?= htmlspecialchars($question['question']) ?></p>
                                
                                <div class="list-group mb-4">
                                    <?php foreach ($options as $option): ?>
                                        <label class="list-group-item list-group-item-action">
                                            <input type="radio" name="answer" value="<?= htmlspecialchars($option) ?>" 
                                                   class="form-check-input me-2" required>
                                            <?= htmlspecialchars($option) ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <?= $current < count($_SESSION['quiz_questions']) - 1 ? 'Next Question' : 'Finish Quiz' ?>
                                </button>
                            </form>
                        <?php 
                            else:
                                echo '<div class="alert alert-warning">Error loading question options. Please try again.</div>';
                            endif;
                        else:
                            // Redirect to results if we've run out of questions
                            $_SESSION['quiz_complete'] = true;
                            header('Location: quizzes.php');
                            exit;
                        endif;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 