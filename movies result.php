<?php
    session_start();

    $currentTopic = isset($_SESSION['current_topic']) ? $_SESSION['current_topic'] : 'movies';

    // Ensure session variables are set
    $playerName = isset($_SESSION['player_name']) ? $_SESSION['player_name'] : 'Guest';
    $selectedQuestions = isset($_SESSION['selected_questions']) ? $_SESSION['selected_questions'] : [];
    $answers = isset($_SESSION['answers']) ? $_SESSION['answers'] : [];

    // Initialize or update the total score from the session
    if (!isset($_SESSION['total_score'])) {
        $_SESSION['total_score'] = 0;
    }

    // Initialize score updated flag if not set
    if (!isset($_SESSION['score_updated'])) {
        $_SESSION['score_updated'] = false;
    }

    // Calculate correct and wrong counts
    $correctCount = 0;
    $wrongCount = 0;

    foreach ($selectedQuestions as $index => $questionData) {
        $correctAnswer = $questionData['answer'];
        $userAnswer = isset($answers[$index]) ? $answers[$index] : '';

        if ($userAnswer === $correctAnswer) {
            $correctCount++;
        } else {
            $wrongCount++;
        }
    }

    // Calculate the points from the current quiz
    $currentQuizPoints = ($correctCount * 3) - ($wrongCount * 1);

    // Update the latest score
    $_SESSION['latest_score'] = $currentQuizPoints;

    // Check if the score should be updated
    if ($_SESSION['score_updated'] === false) {
        // Update the total score
        $_SESSION['total_score'] += $currentQuizPoints;

        // Update the overall scores file
        $Overall_Scores_File = "data/Result.txt"; 
        $updatedScores = [];
        $found = false;

        if (file_exists($Overall_Scores_File)) {
            $scores = file($Overall_Scores_File, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($scores as $line) {
                list($name, $score) = explode(': ', trim($line));
                if ($name === $playerName) {
                    // Add the current quiz points to the player's overall score
                    $newOverallScore = $score + $currentQuizPoints;
                    $updatedScores[] = $playerName . ': ' . $newOverallScore;
                    $found = true;
                } else {
                    $updatedScores[] = $line;
                }
            }
        }

        
        if (!$found) {
            $updatedScores[] = $playerName . ': ' . $currentQuizPoints;
        }

        // Write updated scores to the file
        $result = file_put_contents($Overall_Scores_File, implode(PHP_EOL, $updatedScores));
        if ($result === false) {
            echo "<p>Error writing to scores file.</p>";
        }

        // Mark the score as updated
        $_SESSION['score_updated'] = true;
    }

    // Retrieve the total score
    $totalScore = $_SESSION['total_score'];

    // Error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['start_quiz'])) {
            if (isset($_POST['topic']) && $_POST['topic'] !== '') {
                $topic = $_POST['topic'];
                $_SESSION['current_topic'] = $topic; 
                $_SESSION['score_updated'] = false;
                if ($topic === 'movies') {
                    header('Location: movies quiz.php');
                    exit;
                } elseif ($topic === 'sports') {
                    header('Location: sports quiz.php');
                    exit;
                }
            } else {
                echo "<p style='color: red; text-align: center;'>Please select a valid topic to start the quiz.</p>";
            }
        } elseif (isset($_POST['leaderboard'])) {
            header('Location: leaderboard.php');
            exit;
        } elseif (isset($_POST['exit'])) {
            header('Location: exit.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Results</title>
    <style>
        body {
            margin: 25px;
            font-family: Arial, sans-serif;
        }
        h1 {
            background-color: #FFD580;
            font-weight: bold; 
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .radio-button {
            transform: scale(1.5); 
            margin-right: 15px;
            cursor: pointer;
        }
        .radio-buttons-container {
            text-align: center; 
            margin: 20px;
        }
        .radio-buttons-container label {
            display: inline-block;
            margin: 0 30px; 
        }
        .container {
            margin: 20px;
            padding: 20px;
            border: 2px solid #FFD580;
            border-radius: 5px;
            width: 100%;
            max-width: 900px;
            margin: 0 auto; 
        }
		.container1 {
			text-align: center;
		}
        .form-buttons {
            text-align: center; 
            margin-top: 20px;
        }
        .form-buttons button {
            margin: 0 20px; 
        }
        .start-quiz {
            background-color: #4CAF50; 
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .start-quiz:hover {
            background-color: #45a049;
        }
        .leaderboard {
            background-color: #FFC107; 
            color: black;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .leaderboard:hover {
            background-color: #e0a800;
        }
        .exit {
            background-color: #f44336; 
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .exit:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quiz Results - Movies</h1>

        <!-- Display the results in a table -->
        <table>
            <tr>
                <th>Player Name</th>
                <td><?php echo htmlspecialchars($playerName); ?></td>
            </tr>
            <tr>
                <th>Correct Answers</th>
                <td><?php echo $correctCount; ?></td>
            </tr>
            <tr>
                <th>Wrong Answers</th>
                <td><?php echo $wrongCount; ?></td>
            </tr>
            <tr>
                <th>Current Score</th>
                <td><?php echo $currentQuizPoints; ?></td>
            </tr>
            <tr>
                <th>Overall Score</th>
                <td><?php echo $totalScore; ?></td>
            </tr>
        </table>

        <!-- Instruction line and radio buttons inside the form -->
        <div class="container1">
            <form method="post">
                <p>Please select a topic if you want to continue the game:</p>
                <div class="radio-buttons-container">
                    <label>
                        <input type="radio" name="topic" value="movies" class="radio-button"> Movies
                    </label>
                    <label>
                        <input type="radio" name="topic" value="sports" class="radio-button"> Sports
                    </label>
                </div>
                <!-- Buttons for form submission -->
                <div class="form-buttons">
                    <button type="submit" name="start_quiz" class="start-quiz">Start Quiz</button>
                    <button type="submit" name="leaderboard" class="leaderboard">Leaderboard</button>
                    <button type="submit" name="exit" class="exit">Exit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
