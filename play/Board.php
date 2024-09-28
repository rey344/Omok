<?php
class Board {
    private $grid;  // 2D array representing the board
    private $size = 15;  // Board size is fixed at 15x15

    // Constructor to initialize an empty board
    public function __construct() {
        $this->initializeBoard();  // Set up an empty 15x15 board with 0s
    }

    // Method to initialize or reset the board with empty values (0)
    public function initializeBoard() {
        $this->grid = array_fill(0, $this->size, array_fill(0, $this->size, 0));
    }

    public function placeStone($x, $y, $player = 1) {
        if ($this->isWithinBounds($x, $y) && $this->grid[$x][$y] == 0) {    // Check if cell is empty
            $this->grid[$x][$y] = $player; // Mark the cell with the player's value (default 1)
            return true; // Successfully placed
        }
        return false; // Placement failed (out of bounds or cell already occupied);
    }

    // Method to check if coodinates are within board bounds
    private function isWithinBounds($x, $y) {
        return $x >= 0 && $x < $this->size && $y >= 0 && $y < $this->size;
    }
    // Method to get the current state of the board
    public function getBoard() {
        return $this->grid;
    }

    // Method to display the board (console or simple text representation)
    public function displayBoard() {
        for ($i = 0; $i < $this->size; $i++) {
            for ($j = 0; $j < $this->size; $j++) {
                echo $this->grid[$i][$j] . " ";
            }
            echo "\n";  // New line for each row
        }
    }

    // Method to get the board size
    public function getSize() {
        return $this->size;
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

