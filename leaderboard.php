<?php
session_start();

$Player_Scores_File = 'data/Result.txt';
$playerScores = [];

// Load player scores from the file
if (file_exists($Player_Scores_File)) {
    $file = fopen($Player_Scores_File, 'r');
    if ($file) {
        while (($line = fgets($file)) !== false) {
            list($playerName, $score) = explode(': ', trim($line));
            $playerScores[] = [
                'player_name' => $playerName,
                'score' => (int)$score,
            ];
        }
        fclose($file);
    } else {
        echo "<p>Error: Unable to open file.</p>";
    }
} else {
    echo "<p>No player scores available.</p>";
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sort'])) {
        $sortOption = $_POST['sort'];

        if ($sortOption === 'nickname') {
            usort($playerScores, function($a, $b) {
                return strcasecmp($a['player_name'], $b['player_name']);
            });
        } elseif ($sortOption === 'score') {
            usort($playerScores, function($a, $b) {
                return $b['score'] - $a['score'];
            });
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <style>
        body {
            margin: 20px;
            font-family: Arial, sans-serif;
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
        .container {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        td {
            background-color: #ffffff;
            border: 1px solid #ddd;
        }
        h1 {
            background-color: #FFD580;
            font-weight: bold; 
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            margin-right: 10px;
        }
        .form-group button {
            padding: 0px 20px;
            font-size: 15px;
            font-weight: bold;
        }
        .backtoquiz-button {
            background-color: #4CAF50; 
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .backtoquiz-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Leaderboard</h1>
        <form method="post">
            <div class="form-group">
                <label>Sort by:</label>
                <label>
                    <input type="radio" name="sort" value="nickname" <?php if (isset($_POST['sort']) && $_POST['sort'] === 'nickname') echo 'checked'; ?>> Nickname
                </label>
                <label>
                    <input type="radio" name="sort" value="score" <?php if (!isset($_POST['sort']) || (isset($_POST['sort']) && $_POST['sort'] === 'score')) echo 'checked'; ?>> Score
                </label>
                <button type="submit" name="sort_submit">Sort</button>
            </div>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Player Name</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($playerScores)): ?>
                    <?php foreach ($playerScores as $player): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($player['player_name']); ?></td>
                            <td><?php echo htmlspecialchars($player['score']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No scores to display.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <div class = "container1">
        <form method="post" action="<?php echo isset($_SESSION['current_topic']) && $_SESSION['current_topic'] === 'sports' ? 'sports result.php' : 'movies result.php'; ?>">
            <button type="submit" name="backtoquiz" class="backtoquiz-button">Back to Quiz Result</button>
        </form>
        </div>
    </div>
</body>
</html>
