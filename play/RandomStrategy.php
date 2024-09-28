<?php

class RandomStrategy extends MoveStrategy {

public function pickPlace() {
	$availableMoves = [];  

	// Loop through the board to find available places
	for ($i = 0; $i < count($this->board); $i++) {
		for ($j = 0; $j < count($this->board[$i]); $j++) {
			if ($this->board[$i][$j] === 0) {
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
<?php
