<?php


// Include appropriate classes
require_once 'MoveStrategy.php';
require_once 'RandomStrategy.php';

$board = [
       [false, true, false], //false = empty, true = occupied
       [true, false, false],
       [false, false, true]
];

$strategy = new RandomStrategy($board);

$move = $strategy->pickPlace();

echo "Ramdomly selected move: "
print_r($move);

if($board[$move[0]][$move[1]] == false) {
			      echo "The move is valid\n";
			     } else {
			       	    echo "The move is invalid\n";
			       }
?>