<?php
    session_start();

    // Initialize the player name variable
    $Player_Name = "";

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['player_name'])) {
            echo "<p style='color: red;'>Please enter a name to start the game!</p><br>";
        } else {
             if (file_exists($Player_Name_File) && filesize($Player_Name_File) > 0) {
                $Player_Name_Array = file($Player_Name_File, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $Existing_Player_Name = array_map('trim', $Player_Name_Array);
            }
        
            // Check if player name is new or existing
            $Player_Name = stripslashes(trim($_POST['player_name']));
            $Existing_Player_Name = array();
        
            // Store player name in session
            $_SESSION['player_name'] = $Player_Name;
            
            // Initialize or reset the total score
            $_SESSION['total_score'] = $totalScore;
            
            // Redirect based on the selected topic
            if (isset($_POST['topic'])) {
                $selectedTopic = $_POST['topic'];
                if ($selectedTopic == 'Movie') {
                    header('Location: movies quiz.php'); // Redirect to the movies quiz
                    exit;
                } elseif ($selectedTopic == 'Sport') {
                    header('Location: sports quiz.php'); // Redirect to the sports quiz
                    exit;
                }
            } else {
                echo "<p style='color: red;'>Please select a topic!</p><br>";
            }
        }
    } else {
        // Clear session data to ensure the start menu is shown
        session_unset();
        session_destroy();
        session_start();
    }
?>
    
<!DOCTYPE html>
<html>
<head>
    <title>Main Screen</title>
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
            margin-right: 10px;
        }
        .radio-button {
            transform: scale(1.8); 
            margin-right: 5px;
            cursor: pointer;
        }
        .radio-buttons-container {
            text-align: center; 
            margin-top: 20px;
        }
        .radio-buttons-container label {
            display: inline-block;
            margin: 0 30px; 
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
        .text-box {
            width: 80%; 
            margin-left: 10px;
            padding: 15px; 
            font-size: 18px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Challenge FUN Game</h1>
        <form action="home.php" method="POST">
            <label for="player_name" style="font-weight:bold">Player Name</label>
            <input type="text" id="player_name" name="player_name" class="text-box" value="<?php echo htmlspecialchars($Player_Name); ?>" required />
            <br><br>

            <div class="radio-buttons-container">
                <label>
                    <input type="radio" name="topic" value="Movie" class="radio-button" required />
                    <span class="radio-label">Movie</span>
                </label>
            
                <label>
                    <input type="radio" name="topic" value="Sport" class="radio-button" required />
                    <span class="radio-label">Sport</span>
                </label>
            </div>

            <div class="form-buttons">
                <button type="submit" name="submit" class="submit">Start</button>
            </div>
        </form>
    </div>
</body>
</html>

