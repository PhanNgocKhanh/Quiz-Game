<?php
session_start();

// Check if player name and score are set
if (!isset($_SESSION['player_name']) || !isset($_SESSION['total_score'])) {
    header('Location: home.php');
    exit;
}

// Retrieve player name and total score from session
$playerName = $_SESSION['player_name'];
$totalScore = $_SESSION['total_score'];

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exit</title>
    <style>
        body {
            margin: 25px;
            font-family: Arial, sans-serif;
            overflow: hidden; /* Prevent scrolling when modal is active */
        }
        h2 {
            background-color: #FFD580;
            font-weight: bold;
            text-align: center;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: flex-start; /* Align items at the top */
            justify-content: center;
            z-index: 1000;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            max-width: 900px;
            margin: 20px; /* Apply margin */
            border: 2px solid #FFD580;
        }
        .submit {
            background-color: #4CAF50; 
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            text-decoration: none; /* Remove underline */
        }
        .submit:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            margin: 0 auto; /* Center align the table */
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="modal-overlay">
    <div class="modal-content">
        <h2>Game Over</h2>
        <table>
            <tr>
                <th>Player Name</th>
                <td><?php echo htmlspecialchars($playerName); ?></td>
            </tr>
            <tr>
                <th>Total Score</th>
                <td><?php echo htmlspecialchars($totalScore); ?></td>
            </tr>
        </table>
        <br>
        <a href="home.php" class="submit">Restart</a>
    </div>
</div>
</body>
</html>