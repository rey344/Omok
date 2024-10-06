<?php
/**
 * Abstract class defining the structure for move strategies in the game.
 *
 * This class provides a framework for implementing different move strategies
 * for the game, ensuring that all strategies conform to a standard interface.
 */
abstract class MoveStrategy {
    /**
     * @var Board Holds a reference to the board to allow strategy implementations
     *            to access and interact with the game board.
     */
    protected $board;

    /**
     * Constructs a MoveStrategy instance with a specific game board.
     *
     * @param Board $board The game board on which the moves will be executed,
     *                     ensuring the strategy has context on the current state.
     */
    function __construct(Board $board) {
        $this->board = $board;
    }

    /**
     * Abstract method that must be implemented by concrete strategy classes.
     * It should contain logic to pick and return the next move as a coordinate array.
     *
     * @return array Coordinates for the next move (e.g., ['x' => 5, 'y' => 3]).
     */
    abstract function pickPlace();

    /**
     * Serializes the current strategy object to JSON format, including the class name.
     * Useful for debugging or storing the strategy's state.
     *
     * @return array Associative array with 'name' key containing the class name of the strategy.
     */
    function toJson() {
        return array('name' => get_class($this));
    }

    /**
     * Static method to deserialize a JSON object back into a strategy object.
     * This method assumes the JSON contains enough information to instantiate the strategy.
     *
     * @return MoveStrategy An instance of a subclass of MoveStrategy.
     */
    static function fromJson() {
        $strategy = new static(); // Assumes JSON contains enough data to determine the specific strategy class.
        return $strategy;
    }
}

?>
