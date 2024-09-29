<?php
require_once 'Board.php';
require_once 'MoveStrategy.php';
require_once 'RandomStrategy.php';
require_once 'SmartStrategy.php';
// Define the board
$board = new Board();
$strategy = new RandomStrategy($board);

$move = $strategy->pickPlace();

echo "Randomly selected move: ";
print_r($move);

if ($move != null) {
    $board->placeStone($move[0], $move[1]);
}
echo "Board state after the move:\n";
$board->displayBoard();
?>

<?php
