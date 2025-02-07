<?php
session_start();


if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, ''); 
    $_SESSION['current_player'] = 'X'; 
}


function check_victory($board, $player) {
  
    $winning_combinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], 
        [0, 3, 6], [1, 4, 7], [2, 5, 8],  
        [0, 4, 8], [2, 4, 6]              
    ];

    foreach ($winning_combinations as $combination) {
        if ($board[$combination[0]] == $player &&
            $board[$combination[1]] == $player &&
            $board[$combination[2]] == $player) {
            return true;
        }
    }
    return false;
}


if (isset($_POST['move'])) {
    $index = $_POST['move'];
    
    
    if ($_SESSION['board'][$index] == '') {
        $_SESSION['board'][$index] = $_SESSION['current_player'];
        
       
        if (check_victory($_SESSION['board'], $_SESSION['current_player'])) {
            $message = "Le joueur " . $_SESSION['current_player'] . " a gagné!";
            session_destroy();
        } elseif (empty(array_filter($_SESSION['board'], fn($cell) => $cell === ''))) {
            $message = "C'est un match nul!";
            session_destroy();
        } else {
           
            $_SESSION['current_player'] = ($_SESSION['current_player'] == 'X') ? 'O' : 'X';
        }
    }
}


if (isset($message)) {
    echo "<div><strong>$message</strong></div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic-Tac-Toe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .game-board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            gap: 5px;
        }
        .game-board button {
            width: 100px;
            height: 100px;
            font-size: 24px;
            text-align: center;
            background-color: #f4f4f4;
            border: 2px solid #ccc;
            cursor: pointer;
        }
        .game-board button:active {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div>
        <h1>Tic-Tac-Toe</h1>
        <div class="game-board">
            <?php
          
            foreach ($_SESSION['board'] as $index => $value) {
                echo "<form method='POST' style='margin: 0; padding: 0;'>
                        <button type='submit' name='move' value='$index' " . ($value != '' ? 'disabled' : '') . ">$value</button>
                      </form>";
            }
            ?>
        </div>
    </div>
</body>
</html>