<?php

require_once 'MoveStrategy.php';
require_once 'RandomStrategy.php';

// Define the board
$board = [
    [0, 1, 0], // false = empty, true = occupied
    [1, 0, 0],
    [0, 0, 1]
];

$strategy = new RandomStrategy($board);

$move = $strategy->pickPlace();

echo "Randomly selected move: ";
print_r($move);

if ($board[$move[0]][$move[1]] == 0) {
    echo "The move is valid\n";
} else {
    echo "The move is invalid\n";
}
?>
    

<?php
