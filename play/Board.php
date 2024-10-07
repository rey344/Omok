<?php

/**
 * Represents the game board for Omok, managing the state and rules of the board.
 */
class Board {
    /**
     * @var array A 2D array representing the board's current state where each cell's
     * value indicates the presence and type of a piece (0 for empty, 1 for player 1, 2 for player 2).
     */
    private $grid;

    /**
     * @var int The size of the board, which is fixed at 15x15 cells.
     */
    private $size = 15;

    /**
     * Constructor initializes a new empty board by filling it with zeros.
     */
    public function __construct() {
        $this->initializeBoard();  // Sets up an empty 15x15 board with 0s.
    }

    /**
     * Sets the state of the board to a specified configuration if it matches the expected dimensions.
     *
     * @param array $board A 2D array representing the new state of the board.
     */
    public function setBoard(array $board) {
        if (count($board) === $this->size && count($board[0]) === $this->size) {
            $this->grid = $board; // Updates the grid with a new board state.
        } else {
            echo "Error: Board size mismatch. Expected a {$this->size}x{$this->size} array.\n";
        }
    }
    
    /**
     * Resets the board with empty values (0), indicating no pieces are placed.
     */
    public function initializeBoard() {
        $this->grid = array_fill(0, $this->size, array_fill(0, $this->size, 0));
    }

    /**
     * Attempts to place a stone on the board at specified coordinates for a given player.
     *
     * @param int $x The x-coordinate where the stone is to be placed.
     * @param int $y The y-coordinate where the stone is to be placed.
     * @param int $player The player number placing the stone.
     * @return bool True if the stone was placed successfully, false otherwise.
     */
    public function placeStone($x, $y, $player = 1) {
        if ($this->isMoveValid($x, $y)) {
            $this->grid[$x][$y] = $player; // Marks the cell with the player's value.
            return true;
        }
        return false;
    }

    /**
     * Validates if a move is within the board bounds and the spot is not already occupied.
     *
     * @param int $x The x-coordinate to check.
     * @param int $y The y-coordinate to check.
     * @return bool True if the move is within bounds and the spot is available, false otherwise.
     */
    public function isMoveValid($x, $y) {
        return $this->isWithinBounds($x, $y) && $this->grid[$x][$y] === 0;
    }

    /**
     * Checks if specified coordinates are within the valid range of the board.
     *
     * @param int $x The x-coordinate to verify.
     * @param int $y The y-coordinate to verify.
     * @return bool True if both coordinates are within the board's bounds, false otherwise.
     */
    private function isWithinBounds($x, $y) {
        return $x >= 0 && $x < $this->size && $y >= 0 && $y < $this->size;
    }

    /**
     * Provides the current configuration of the board.
     *
     * @return array A 2D array representing the current board state.
     */
    public function getBoard() {
        return $this->grid;
    }

    

    /**
     * Outputs the board to the console for debugging or display purposes, showing its current state.
     */
    public function displayBoard() {
        for ($i = 0; $i < $this->size; $i++) {
            for ($j = 0; $j < $this->size; $j++) {
                echo $this->grid[$i][$j] . " ";
            }
            echo "\n"; // New line for each row
        }
    }

    /**
     * Checks if the last move resulted in a win.
     *
     * @param int $x The x-coordinate of the last move.
     * @param int $y The y-coordinate of the last move.
     * @param int $player The player number who made the move.
     * @return bool True if the move created a winning line of five consecutive stones, false otherwise.
     */
    public function checkWin($x, $y, $player) {
        $directions = [
            [[1, 0], [-1, 0]], // Horizontal
            [[0, 1], [0, -1]], // Vertical
            [[1, 1], [-1, -1]], // Diagonal \
            [[1, -1], [-1, 1]]  // Diagonal /
        ];
    
        for ($d = 0; $d < count($directions); $d++) {
            $count = 1; // Includes the current stone
            for ($i = 0; $i < count($directions[$d]); $i++) {
                $dx = $directions[$d][$i][0];
                $dy = $directions[$d][$i][1];
                $count += $this->countInDirection($x, $y, $player, $dx, $dy);
            }
            if ($count >= 5) {
                return true; // Win condition met
            }
        }
        return false; // No win found
    }
    

    /**
     * Helper method to count consecutive stones in one direction from a given start point.
     *
     * @param int $x Starting x-coordinate.
     * @param int $y Starting y-coordinate.
     * @param int $player The player's number.
     * @param int $dx The horizontal step for each iteration.
     * @param int $dy The vertical step for each iteration.
     * @return int The number of consecutive stones.
     */
    private function countInDirection($x, $y, $player, $dx, $dy) {
        $count = 0;
        $i = $x + $dx;
        $j = $y + $dy;
        while ($this->isWithinBounds($i, $j) && $this->grid[$i][$j] == $player) {
            $count++;
            $i += $dx;
            $j += $dy;
        }
        return $count;
    }

    /**
     * Determines if the board is completely filled without any empty spaces, indicating a draw.
     *
     * @return bool True if no empty spaces are available, indicating a draw; false otherwise.
     */
    public function checkDraw() {
        for ($i = 0; $i < $this->size; $i++) {
            for ($j = 0; $j < $this->size; $j++) {
                if ($this->grid[$i][$j] === 0) {
                    return false; // Found an empty spot, so not a draw
                }
            }
        }
        return true; // No empty spots, it's a draw
    }
}
?>
