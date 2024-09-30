<?php
session_start();

// Open the Movie_Question file for reading
$Question_Array = array();
$file = fopen('data/Movie_Question.txt', 'r');

if ($file) {
    while (($line = fgets($file)) !== false) {
        list($question, $answer) = explode(': ', trim($line));
        $Question_Array[] = array('question' => $question, 'answer' => $answer);
    }
    fclose($file);
} else {
    echo "Error opening the file!";
    exit;
}

// Randomly select 4 questions if not already selected
if (!isset($_POST['submit'])) {
    if (count($Question_Array) > 4) {
        $random_keys = array_rand($Question_Array, 4);
        $selected_questions = array_intersect_key($Question_Array, array_flip($random_keys));
    } else {
        $selected_questions = $Question_Array;
    }
    $_SESSION['selected_questions'] = $selected_questions;
} else {
    $selected_questions = $_SESSION['selected_questions'];
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    $Answers = $_POST['ans'];

    // Update the session with answers
    $_SESSION['answers'] = $Answers;

    // Redirect to the result page after completing the quiz
    header('Location: movies result.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movie Quiz</title>
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
        .radio-label {
            font-size: 15px;
            margin-right: 5px;
        }
        .radio-button {
            transform: scale(1.5);
            margin-right: 5px;
            cursor: pointer;
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
            margin-bottom: 20px;
        }
        td {
            padding: 10px;
            text-align: left;
        }
        .question-cell {
            width: 80%; /* Adjust width as needed */
        }
        .radio-cell {
            width: 10%; /* Adjust width as needed */
            text-align: center;
        }
        tr {
            border-bottom: 1px solid #ddd;
        }
        .question-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px; /* Adjust height as needed */
        }
        .radio-container {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quiz - Movie</h1>
        <form method="post" action="">
            <table>
                <?php
                $questionNumber = 1;
                foreach ($selected_questions as $index => $questionData) {
                    $questionText = $questionData['question'];
                    echo "<tr class='question-row'>";
                    echo "<td class='question-cell'><strong>Question $questionNumber:</strong> " . htmlspecialchars($questionText) . "</td>";
                    echo "<td class='radio-cell'><div class='radio-container'><label class='radio-label'><input type='radio' name='ans[$index]' value='True' class='radio-button' required> True</label></div></td>";
                    echo "<td class='radio-cell'><div class='radio-container'><label class='radio-label'><input type='radio' name='ans[$index]' value='False' class='radio-button' required> False</label></div></td>";
                    echo "</tr>";
                    $questionNumber++;
                }
                ?>
            </table>
            <div class="form-buttons">
                <input type="submit" name="submit" value="Submit" class="submit">
            </div>
        </form>
    </div>
</body>
