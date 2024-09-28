<?php

class RandomStrategy extends MoveStrategy {

public function pickPlace() {
	$availableMoves = [];  

    $boardState = $this->board->getBoard();
    
	// Loop through the board to find available places
	for ($i = 0; $i < $this->board->getSize(); $i++) {
		for ($j = 0; $j < $this->board->getSize(); $j++) {
			if ($this->board->isMoveValid($i, $j)) {
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


?>
