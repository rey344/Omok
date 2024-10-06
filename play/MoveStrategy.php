<?php
/**
 * Abstract class defining the structure for move strategies in the Omok game.
 * Provides a framework for implementing different move strategies, ensuring all strategies
 * conform to a standard interface and can interact directly with the game board.
 */
abstract class MoveStrategy {
    /**
     * @var Board Holds a reference to the board to allow strategy implementations
     *            to access and interact with the game board.
     */
    protected $board;

    /**
     * Constructs a MoveStrategy instance with a specific game board.
     * This setup ensures that the strategy has full context of the current game state, 
     * enabling it to make informed decisions based on the board configuration.
     *
     * @param Board $board The game board on which the moves will be executed.
     */
    function __construct(Board $board) {
        $this->board = $board;
    }

    /**
     * Abstract method to pick and return the next move as a coordinate array.
     * Each strategy must implement this method to decide the next best move based on the current board state.
     *
     * @return array|null Coordinates for the next move (e.g., ['x' => 5, 'y' => 3]) or null if no valid move is possible.
     */
    abstract public function pickPlace();

    /**
     * Applies a specified move to the game board, updating the board's state.
     * This method allows strategies to execute moves directly, ensuring that the game state is consistently updated.
     *
     * @param int $x The x-coordinate of the move.
     * @param int $y The y-coordinate of the move.
     * @param int $player The player number making the move.
     * @return bool True if the move was successfully made, false otherwise (e.g., move out of bounds or spot already taken).
     */
    public function applyMove($x, $y, $player) {
        return $this->board->placeStone($x, $y, $player);
    }

    /**
     * Serializes the current strategy object to JSON format, typically including the class name.
     * This method is useful for debugging, logging, or storing the strategy's state.
     *
     * @return string JSON encoded string representing the strategy state.
     */
    public function toJson() {
        return json_encode(['name' => get_class($this)]);
    }

    /**
     * Static method to deserialize a JSON object back into a strategy object.
     * Assumes the JSON string contains enough information to instantiate a specific strategy class.
     *
     * @param string $json JSON string representing a strategy object.
     * @return MoveStrategy An instance of a subclass of MoveStrategy.
     * @throws Exception If the JSON is invalid or does not contain necessary information.
     */
    static function fromJson($json) {
        $data = json_decode($json, true);
        if (!isset($data['name'])) {
            throw new Exception("Invalid JSON data for strategy: Missing 'name'.");
        }
        if (!class_exists($data['name'])) {
            throw new Exception("Invalid strategy class specified: " . $data['name']);
        }
        $strategy = new $data['name'](new Board()); // Example instantiation, adjust as necessary
        return $strategy;
    }
}
?>
