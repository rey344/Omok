<?php

class RandomStrategy extends MoveStrategy {

public function pickPlace() {
	$availableMoves = [];  

    $boardState = $this->board->getBoard();
    
	// Loop through the board to find available places
	for ($i = 0; $i < count($boardState); $i++) {
		for ($j = 0; $j < count($boardState[$i]); $j++) {
			if ($boardState[$i][$j] === 0) {
				$availableMoves[] = [$i, $j]; // add coordinates to availableMoves
			}
		}
	}

	// if there are available moves, pick one randomly
	if (!empty($availableMoves)) {
		$randomIndex = array_rand($availableMoves); // select a random move
		return $availableMoves[$randomIndex]; // return the row and column of the move
	} else {
		return null; // no available moves
	}
}
}

$board = new Board();
$strategy = new RandomStrategy($board);
$move = $strategy-> pickPlace();
if ($move != null) {
    $board->placeStone($move[0], $move[1]);
}
$board->displayBoard();
?>
