<?php
session_start();

// Initialisation de la grille
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, ''); // 9 cases vides
    $_SESSION['turn'] = 'X'; // Le joueur X commence
    $_SESSION['winner'] = null;
}

// Fonction pour afficher la grille
function displayBoard() {
    $board = $_SESSION['board'];
    echo "<table border='1'>";
    for ($i = 0; $i < 3; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 3; $j++) {
            $index = $i * 3 + $j;
            echo "<td style='width: 50px; height: 50px; text-align: center;' onclick='makeMove($index)'>";
            echo $board[$index] ? $board[$index] : "&nbsp;";
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

// Vérification du gagnant
function checkWinner() {
    $board = $_SESSION['board'];
    $winning_combinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // Lignes
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // Colonnes
        [0, 4, 8], [2, 4, 6] // Diagonales
    ];

    foreach ($winning_combinations as $combination) {
        if ($board[$combination[0]] && $board[$combination[0]] === $board[$combination[1]] && $board[$combination[0]] === $board[$combination[2]]) {
            return $board[$combination[0]];
        }
    }

    // Vérifier si la grille est pleine
    if (!in_array('', $board)) {
        return 'Draw';
    }

    return null;
}

// Effectuer un mouvement
if (isset($_GET['move'])) {
    $move = $_GET['move'];

    if ($_SESSION['board'][$move] === '') {
        $_SESSION['board'][$move] = $_SESSION['turn'];
        $_SESSION['turn'] = ($_SESSION['turn'] === 'X') ? 'O' : 'X';
    }

    $_SESSION['winner'] = checkWinner();
}

// Affichage du message de victoire ou de match nul
if ($_SESSION['winner']) {
    if ($_SESSION['winner'] === 'Draw') {
        echo "<p>Match nul !</p>";
    } else {
        echo "<p>Le joueur {$_SESSION['winner']} a gagné !</p>";
    }
    echo "<a href='?reset=1'>Recommencer</a>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Morpion</title>
    <style>
        table { border-collapse: collapse; }
        td { cursor: pointer; }
    </style>
    <script>
        function makeMove(index) {
            window.location.href = "?move=" + index;
        }
    </script>
</head>
<body>
    <h1>Jeu de Morpion</h1>
    <?php displayBoard(); ?>

    <p>Tour de : <?php echo $_SESSION['turn']; ?></p>

    <?php
    // Réinitialiser le jeu
    if (isset($_GET['reset'])) {
        session_destroy();
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
    ?>
</body>
</html>
