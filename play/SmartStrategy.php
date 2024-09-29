<?php
class SmartStrategy extends MoveStrategy {
    // Method to pick a smart place on the board
    public function pickPlace() {

        $boardState = $this->board->getBoard(); // Get the current board state
        $opponent = 2; // Assuming player 2 is the opponent

        // Step 1: Check if the strategy can win by placing a stone
        foreach ($boardState as $x => $row) {
            foreach ($row as $y => $cell) {
                if ($this->board->isMoveValid($x, $y)) {
                    if ($this->canWin($x, $y, 1)){
                        return [$x, $y]; // Make the winning move
                    }
                }
            }
        }

        // Step 2: Check if the strategy needs to block an opponent's winning move
        foreach ($boardState as $x => $row) {
            foreach ($row as $y => $cell) {
                if ($this->board->isMoveValid($x, $y)) {                    
                    if ($this->canWin($x, $y, $opponent)) { // Check if the opponent can win
                        return [$x, $y]; // Block the opponent move
                    }
                }
            }
        }


    // Step 3: Fallback to a random move if no winning/blocking move is found
    
    $randomStrategy = new RandomStrategy($this->board);
    return $randomStrategy->pickPlace();
    }
    
    // Helper method to check if placing a stone results in a win
    private function canWin($x, $y, $player){
        // Temporarily place the stone and check for a win
        $this->board->placeStone($x, $y, $player);
        $isWinningMove = $this->isWinningMove($x, $y, $player);
        $this->board->placeStone($x, $y, 0); // Reset the cell after the check
        return $isWinningMove;
    }

    // Method to check if a given position results in a win for the player
    private function isWinningMove($x, $y, $player) {
        // Check rows, columns, diagonals for a win condition
        return $this->checkDirection($x, $y, $player, 1, 0) || // Check row
               $this->checkDirection($x, $y, $player, 0, 1) || // Check column
               $this->checkDirection($x, $y, $player, 1, 1) // Check diagonal (down-right)
               $this->checkDirection($x, $y, $player, 1, -1); // Check diagonal (up-right)
    }

    private function checkDirection($x, $y, $player, $dx, $dy){
        $count = 1; // Include the current stone
        // Check in the positive direction
        $count += $this->countStoneInDirection($x, $y, $player, $dx, $dy);
        // Check in the negative direction
        $count += $this->countStoneInDirection($x, $y, $player, -$dx, $dy);
        return $count >= 5; // Return true if there are 5 or more consecutive stones
    }
    // Method to count stones in a given direction
    private function countStoneInDirection($x, $y, $player, $dx, $dy) {
        $count = 0;
        $i = $x + $dx;
        $j = $y + $dy;

        while (this->board->inWithinBounds($i, $j) && this->board->getBoard() === $player) {
            $count++;
            $i += $dx;
            $j += $dy;
        }
        return $count;
    }
}
        
?>
