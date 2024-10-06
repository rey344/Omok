<?php

/**
 * Represents the game board for Omok, holding and managing the state of the board.
 */
class Board {
    /**
     * @var array A 2D array representing the board's current state where each cell's
     * value indicates the presence and type of a piece.
     */
    private $grid;

    /**
     * @var int The size of the board (number of cells in one dimension), which is fixed at 15x15.
     */
    private $size = 15;

    /**
     * Constructor initializes a new empty board.
     */
    public function __construct() {
        $this->initializeBoard();  // Sets up an empty 15x15 board with 0s.
    }

    /**
     * Sets the state of the board with a new configuration if it matches the expected size.
     *
     * @param array $board A 2D array representing the new state of the board.
     */
    public function setBoard(array $board) {
        if (count($board) === $this->size && count($board[0]) === $this->size) {
            $this->grid = $board; // Update the grid with a new board state.
        } else {
            echo "Error: Board size mismatch. Expected a {$this->size}x{$this->size} array.\n";
        }
    }
    
    /**
     * Initializes or resets the board with empty values (0), representing no pieces placed.
     */
    public function initializeBoard() {
        $this->grid = array_fill(0, $this->size, array_fill(0, $this->size, 0));
    }

    /**
     * Attempts to place a stone on the board at the specified coordinates for a given player.
     *
     * @param int $x The x-coordinate where the stone is to be placed.
     * @param int $y The y-coordinate where the stone is to be placed.
     * @param int $player The player number placing the stone.
     * @return bool Returns true if the stone was placed successfully, false otherwise.
     */
    public function placeStone($x, $y, $player = 1) {
        if ($this->isMoveValid($x, $y)) {
            $this->grid[$x][$y] = $player; // Mark the cell with the player's value.
            return true;
        }
        return false;
    }

    /**
     * Checks if the specified move coordinates are within the bounds of the board and the spot is not already taken.
     *
     * @param int $x The x-coordinate to check.
     * @param int $y The y-coordinate to check.
     * @return bool Returns true if the move is within bounds and the spot is available, false otherwise.
     */
    private function isMoveValid($x, $y) {
        return $this->isWithinBounds($x, $y) && $this->grid[$x][$y] === 0;
    }

    /**
     * Determines if the specified coordinates are within the valid range of the board.
     *
     * @param int $x The x-coordinate to check.
     * @param int $y The y-coordinate to check.
     * @return bool Returns true if both coordinates are within the board's bounds, false otherwise.
     */
    private function isWithinBounds($x, $y) {
        return $x >= 0 && $x < $this->size && $y >= 0 && $y < $this->size;
    }

    /**
     * Retrieves the current state of the board.
     *
     * @return array A 2D array representing the current board configuration.
     */
    public function getBoard() {
        return $this->grid;
    }

    /**
     * Prints the board to the console in a readable format for debugging or display purposes.
     */
    public function displayBoard() {
        for ($i = 0; $i < $this->size; $i++) {
            for ($j = 0; $j < $this->size; $j++) {
                echo $this->grid[$i][$j] . " ";
            }
            echo "\n";  // New line for each row
        }
    }
}
// Example usage of the updated minimal board class
//$board = new Board();  // Create a new 15x15 board
//$board->displayBoard();  // Display the empty board with 0s
//$currentState = $board->getBoard();
//print_r($currentState);
//echo json_encode($board->getBoard(), JSON_PRETTY_PRINT);
//var_dump($currentState);
?>

