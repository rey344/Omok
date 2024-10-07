<?php

class RandomStrategy extends MoveStrategy {

    /**
     * Selects a random valid move from the available moves on the board.
     * This method ensures that the move is not only random but also valid,
     * adhering to the rules of the board's current state.
     *
     * @return array|null The coordinates [x, y] of the randomly selected move, or null if no valid moves are available.
     */
    public function pickPlace() {
        $availableMoves = $this->findAvailableMoves();

        // Randomly pick one move from available moves if any are present
        if (!empty($availableMoves)) {
            $randomIndex = array_rand($availableMoves);  // Select a random index from available moves
            return $availableMoves[$randomIndex];  // Return the row and column of the selected move
        }

        return null;  // Return null if no available moves are found
    }

    /**
     * Finds all valid move positions on the board where a stone can be placed.
     * This method scans the entire board and compiles a list of all coordinates where a move is valid.
     *
     * @return array An array of valid move coordinates.
     */
    private function findAvailableMoves() {
        $availableMoves = [];
        $boardSize = $this->board->getSize();  // Get the size of the board to limit the loop

        // Loop through each cell on the board to check for validity
        for ($i = 0; $i < $boardSize; $i++) {
            for ($j = 0; $j < $boardSize; $j++) {
                if ($this->board->isMoveValid($i, $j)) {
                    $availableMoves[] =  ['x' => $i, 'y' => $j];  // Add the valid move to the list
                }
            }
        }

        return $availableMoves;
    }
}

?>
