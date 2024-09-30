<?php
    // Start the session first
    session_start();

    // Open the Sport_Question file for reading
    $Question_Array = array();
    $file = fopen('data/Sport_Question.txt', 'r');

    if ($file) {
        while (($line = fgets($file)) !== false) {
            list($picture, $question, $answer) = explode(', ', trim($line));
            $Question_Array[] = array('picture' => 'images/' . $picture, 'question' => $question, 'answer' => $answer);
        }
        fclose($file);
    } else {
        echo "Error opening the file!";
    }

    // Randomly select 4 questions
    if (!isset($_POST['submit'])) {
        if (count($Question_Array) > 4) {
            $random_keys = array_rand($Question_Array, 4);
            $selected_questions = array($Question_Array[$random_keys[0]], $Question_Array[$random_keys[1]], $Question_Array[$random_keys[2]], $Question_Array[$random_keys[3]]);
        } else {
            // If there are not enough questions, use all available questions
            $selected_questions = $Question_Array;
        }
        $_SESSION['selected_questions'] = $selected_questions;
    } else {
        $selected_questions = $_SESSION['selected_questions'];
    }

    if (isset($_POST['submit'])) {
        $Player_Answer = $_POST['ans'];

        // Update the session with answers
        $_SESSION['player_answers'] = $Player_Answer;

        // Set the current topic in the session
        $_SESSION['current_topic'] = 'sports';

        // Direct to result page
        header('Location: sports result.php');
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sport Quiz</title>
</head>

<body>
    <div class="container">
        <h1>Quiz - Sports</h1>
        <form method="post" action="">
            <table>
                <?php
                    if (!isset($_POST['submit'])) {
                        $questionNumber = 1;
                        foreach ($selected_questions as $index => $question) {
                            echo "<tr>";
                            echo "<td rowspan='2'><img src='{$question['picture']}' style='max-width: 300px; height='100'display: block; margin-left: auto; margin-right: auto;'></td>";
                            echo "<td><strong>Question $questionNumber:</strong> " . htmlspecialchars($question['question']) . "</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><input type='text' name='ans[$index]' class='text-box' placeholder='Enter your answer here'></td>";
                            echo "</tr>";
                            echo "<input type='hidden' name='questions[]' value='{$question['answer']}'>";
                            $questionNumber++;
                        }
                    }
                ?>
            </table>
                
            <div class="form-buttons">
                <input type="submit" name="submit" value="Submit" class="submit">
            </div>
        </form>
    </div>
       
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
        .submit {
            background-color: #4CAF50; 
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .submit:hover {
            background-color: #45a049;
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
        .form-buttons {
            text-align: center; 
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #FFD580;
            font-weight: bold;
        }
        tr {
            border-bottom: 1px solid #ddd;
        }
        .text-box {
            width: 90%; 
            padding: 15px; 
            font-size: 18px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }
    </style>
</body>
</html>

