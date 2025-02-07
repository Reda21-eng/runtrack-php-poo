<?php
session_start();

// Initialiser ou réinitialiser le jeu
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, ''); // tableau vide avec 9 cases
    $_SESSION['turn'] = 'X'; // X commence
    $_SESSION['winner'] = null;
}

function checkWinner($board) {
    // Combinaisons gagnantes
    $winning_combinations = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
        [0, 4, 8],
        [2, 4, 6]
    ];

    foreach ($winning_combinations as $combination) {
        if ($board[$combination[0]] != '' && $board[$combination[0]] == $board[$combination[1]] && $board[$combination[0]] == $board[$combination[2]]) {
            return $board[$combination[0]]; // Retourne le gagnant (X ou O)
        }
    }
    return null; // Aucun gagnant
}

if (isset($_GET['move']) && $_SESSION['winner'] === null) {
    $move = $_GET['move'];
    if ($_SESSION['board'][$move] === '') { // Si la case est vide
        $_SESSION['board'][$move] = $_SESSION['turn']; // Marquer la case avec X ou O
        $_SESSION['turn'] = $_SESSION['turn'] === 'X' ? 'O' : 'X'; // Changer de joueur
        $_SESSION['winner'] = checkWinner($_SESSION['board']);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Morpion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            gap: 5px;
            margin: 20px auto;
            width: 320px;
        }
        .cell {
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #000;
            font-size: 2em;
            cursor: pointer;
        }
        .winner {
            margin-top: 20px;
            font-size: 1.5em;
        }
    </style>
</head>
<body>

<h1>Jeu de Morpion</h1>

<div class="board">
    <?php foreach ($_SESSION['board'] as $index => $cell) : ?>
        <div class="cell" onclick="location.href='?move=<?= $index ?>'"><?= $cell ?></div>
    <?php endforeach; ?>
</div>

<?php if ($_SESSION['winner'] !== null) : ?>
    <div class="winner">Le gagnant est : <?= $_SESSION['winner'] ?></div>
    <a href="">Recommencer le jeu</a>
<?php else : ?>
    <div class="winner">Tour du joueur <?= $_SESSION['turn'] ?></div>
<?php endif; ?>

</body>
</html>
