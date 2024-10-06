<?php

/**
 * Implements an intelligent strategy for move selection based on game state analysis.
 * This strategy determines the best move by assessing potential wins, blocks, or resorting to a random move.
 */
class SmartStrategy extends MoveStrategy {

    /**
     * Evaluates the board and selects the most strategic move available.
     *
     * @return array|null Coordinates of the selected move [x, y], or null if no moves are viable.
     */
    public function pickPlace() {
        $boardState = $this->board->getBoard();
        $opponent = 2;

        // Attempt to find a winning move or block an opponent's winning move
        $bestMove = $this->findStrategicMove($opponent);
        if ($bestMove) {
            return $bestMove;
        }

        // Fallback to a random move if no strategic moves are found
        $randomStrategy = new RandomStrategy($this->board);
        return $randomStrategy->pickPlace();
    }

    /**
     * Searches for a winning or blocking move on the board.
     *
     * @param int $opponent Player number of the opponent.
     * @return array|null Coordinates of the strategic move [x, y], or null if none found.
     */
    private function findStrategicMove($opponent) {
        for ($x = 0; $x < $this->board->getSize(); $x++) {
            for ($y = 0; $y < $this->board->getSize(); $y++) {
                if ($this->board->isMoveValid($x, $y)) {
                    if ($this->canWin($x, $y, 1)) {
                        return [$x, $y];  // Winning move for player
                    } elseif ($this->canWin($x, $y, $opponent)) {
                        return [$x, $y];  // Block opponent's winning move
                    }
                }
            }
        }
        return null;  // No strategic move found
    }

    /**
     * Simulates a move to determine if it results in a win.
     *
     * @param int $x The x-coordinate of the move.
     * @param int $y The y-coordinate of the move.
     * @param int $player The player number.
     * @return bool True if the move results in a win, false otherwise.
     */
    private function canWin($x, $y, $player) {
        $this->board->placeStone($x, $y, $player);
        $winningMove = $this->isWinningMove($x, $y, $player);
        $this->board->placeStone($x, $y, 0);  // Reset the move
        return $winningMove;
    }

    /**
     * Checks all potential directions from a move to see if it creates a line of five consecutive stones.
     *
     * @param int $x The x-coordinate of the move.
     * @param int $y The y-coordinate of the move.
     * @param int $player The player number.
     * @return bool True if the move is a winning move, false otherwise.
     */
    private function isWinningMove($x, $y, $player) {
        return $this->checkDirection($x, $y, $player, 1, 0) ||  // Horizontal
               $this->checkDirection($x, $y, $player, 0, 1) ||  // Vertical
               $this->checkDirection($x, $y, $player, 1, 1) ||  // Diagonal down-right
               $this->checkDirection($x, $y, $player, 1, -1);   // Diagonal up-right
    }
}

?>
