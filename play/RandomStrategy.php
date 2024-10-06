<?php

class RandomStrategy extends MoveStrategy {

    /**
     * Picks a random valid place from the board to place a stone.
     *
     * @return array|null Returns an array with coordinates [x, y] of the chosen move,
     *                    or null if no valid moves are available.
     */
    public function pickPlace() {
        $availableMoves = [];

        // Retrieve the current state of the board
        $boardState = $this->board->getBoard();
        
        // Loop through the board to find available places
        for ($i = 0; $i < $this->board->getSize(); $i++) {
            for ($j = 0; $j < $this->board->getSize(); $j++) {
                // Check if the move at position [i, j] is valid
                if ($this->board->isMoveValid($i, $j)) {
                    // Add coordinates to availableMoves if the move is valid
                    $availableMoves[] = [$i, $j];
                }
            }
        }

        // If there are available moves, pick one randomly
        if (!empty($availableMoves)) {
            $randomIndex = array_rand($availableMoves);  // Select a random index
            return $availableMoves[$randomIndex];  // Return the row and column of the move
        } else {
            return null;  // No available moves
        }
    }
}

?>
