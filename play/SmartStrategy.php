<?php

/**
 * SmartStrategy extends the base MoveStrategy to implement an intelligent move strategy.
 * This strategy aims to make the most optimal move by either winning the game, blocking the opponent,
 * or falling back to a random move if no strategic advantage can be achieved.
 */
class SmartStrategy extends MoveStrategy {

    /**
     * Selects an optimal move on the board based on the current game state.
     * First, it tries to find a winning move for itself. If no immediate winning move is found,
     * it checks to block the opponent's potential winning move. If neither are applicable,
     * it defaults to a random move.
     *
     * @return array|null The coordinates of the chosen move as [x, y], or null if no moves are available.
     */
    public function pickPlace() {
        $boardState = $this->board->getBoard(); // Retrieve the current board state
        $opponent = 2; // Assuming player 2 is the opponent

        // Step 1: Attempt to find a winning move for the current player
        for ($x = 0; $x < $this->board->getSize(); $x++) {
            for ($y = 0; $y < $this->board->getSize(); $y++) {
                if ($this->board->isMoveValid($x, $y) && $this->canWin($x, $y, 1)) {
                    return [$x, $y]; // Execute the winning move
                }
            }
        }

        // Step 2: Attempt to block the opponent's potential winning move
        for ($x = 0; $x < $this->board->getSize(); $x++) {
            for ($y = 0; $y < $this->board->getSize(); $y++) {
                if ($this->board->isMoveValid($x, $y) && $this->canWin($x, $y, $opponent)) {
                    return [$x, $y]; // Block the opponent move
                }
            }
        }

        // Step 3: Fallback to a random move if no winning/blocking move is found
        $randomStrategy = new RandomStrategy($this->board);
        return $randomStrategy->pickPlace();
    }

    /**
     * Helper method to check if placing a stone at specified coordinates results in a win.
     * This method simulates placing a stone by the current player and checks if it creates a winning condition.
     *
     * @param int $x The x-coordinate for the move.
     * @param int $y The y-coordinate for the move.
     * @param int $player The player number (1 or 2).
     * @return bool Returns true if the move results in a win, false otherwise.
     */
    private function canWin($x, $y, $player){
        $this->board->placeStone($x, $y, $player);  // Temporarily place the stone
        $isWinningMove = $this->isWinningMove($x, $y, $player);
        $this->board->placeStone($x, $y, 0);  // Reset the cell after the check
        return $isWinningMove;
    }

    /**
     * Checks if a given move by a player results in a win. This involves checking all possible directions
     * for a line of consecutive stones.
     *
     * @param int $x The x-coordinate of the move.
     * @param int $y The y-coordinate of the move.
     * @param int $player The player number.
     * @return bool Returns true if the move is a winning move, false otherwise.
     */
    private function isWinningMove($x, $y, $player) {
        // Check all four directions to see if the move results in five consecutive stones
        return $this->checkDirection($x, $y, $player, 1, 0) ||  // Check horizontally
               $this->checkDirection($x, $y, $player, 0, 1) ||  // Check vertically
               $this->checkDirection($x, $y, $player, 1, 1) ||  // Check diagonal (down-right)
               $this->checkDirection($x, $y, $player, 1, -1);   // Check diagonal (up-right)
    }

    /**
     * Helper method to count stones in a specific direction from a given start point.
     *
     * @param int $x Start x-coordinate.
     * @param int $y Start y-coordinate.
     * @param int $player The player number.
     * @param int $dx The x-direction increment (1, 0, or -1).
     * @param int $dy The y-direction increment (1, 0, or -1).
     * @return int The count of consecutive stones matching the player's number.
     */
    private function checkDirection($x, $y, $player, $dx, $dy){
        $count = 1; // Include the initial stone
        // Check in the positive direction
        $count += $this->countStoneInDirection($x, $y, $player, $dx, $dy);
        // Check in the negative direction
        $count += $this->countStoneInDirection($x, $y, $player, -$dx, -$dy);
        return $count >= 5;  // Win condition is 5 or more consecutive stones
    }


    /**
     * Counts the consecutive stones of a specific player in a specified direction from a starting position.
     *
     * This method is crucial for determining potential win conditions by assessing lines of connected stones.
     *
     * @param int $x Starting x-coordinate where counting begins.
     * @param int $y Starting y-coordinate where counting begins.
     * @param int $player Identifies the player (e.g., Player 1 or Player 2) whose stones are to be counted.
     * @param int $dx Directional increment for the x-coordinate. It determines the horizontal direction to check:
     *                -1 for left, 0 for no change, +1 for right.
     * @param int $dy Directional increment for the y-coordinate. It determines the vertical direction to check:
     *                -1 for up, 0 for no change, +1 for down.
     * 
     * @return int The number of consecutive stones found starting from the initial coordinates and
     *             moving in the specified direction until a gap or different player's stone is encountered.
     */
    private function countStoneInDirection($x, $y, $player, $dx, $dy) {
        $count = 0;  // Initialize the count of consecutive stones
        $i = $x + $dx;  // Calculate the initial x-coordinate for checking
        $j = $y + $dy;  // Calculate the initial y-coordinate for checking

        // Continue checking in the specified direction as long as the position is within bounds
        // and the stone at the position belongs to the specified player(player 1 = 1, and a placed stone is represented by a 1)
        while ($this->board->isWithinBounds($i, $j) && $this->board->getBoard()[$i][$j] == $player) {
            $count++;    // Increment the count for each matching stone found
            $i += $dx;   // Move to the next position in the x-direction
            $j += $dy;   // Move to the next position in the y-direction
        }

        return $count;  // Return the total count of consecutive stones found
    }


}
