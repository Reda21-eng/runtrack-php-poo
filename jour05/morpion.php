<?php
session_start();

// Initialisation du jeu
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, null);  // Crée un tableau de 9 cases vides
    $_SESSION['player'] = 'X';  // Le joueur X commence
}

// Vérification des cases du tableau
function checkWinner($board) {
    // Liste des combinaisons gagnantes
    $winning_combinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // lignes
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // colonnes
        [0, 4, 8], [2, 4, 6]  // diagonales
    ];
    
    foreach ($winning_combinations as $combination) {
        list($a, $b, $c) = $combination;
        if ($board[$a] !== null && $board[$a] === $board[$b] && $board[$a] === $board[$c]) {
            return $board[$a];  // Retourne le gagnant ('X' ou 'O')
        }
    }
    return null;
}

// Gestion des coups
if (isset($_GET['cell']) && is_numeric($_GET['cell'])) {
    $cell = $_GET['cell'];
    if ($_SESSION['board'][$cell] === null) {  // Si la case est vide
        $_SESSION['board'][$cell] = $_SESSION['player'];  // Assigner le symbole du joueur actuel
        // Changer de joueur
        $_SESSION['player'] = ($_SESSION['player'] === 'X') ? 'O' : 'X';  
    }
}

// Vérification du gagnant
$winner = checkWinner($_SESSION['board']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic-Tac-Toe</title>
    <style>
        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            gap: 5px;
        }
        .cell {
            width: 100px;
            height: 100px;
            text-align: center;
            font-size: 2em;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .cell:empty:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<h1>Tic-Tac-Toe</h1>

<?php if ($winner): ?>
    <p>Le joueur <?= $winner ?> a gagné !</p>
    <a href="index.php">Rejouer</a>
<?php else: ?>
    <p>Au tour de <?= $_SESSION['player'] ?> de jouer</p>

    <div class="board">
        <?php foreach ($_SESSION['board'] as $index => $cell): ?>
            <div class="cell" onclick="window.location='?cell=<?= $index ?>'">
                <?= $cell ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>
''