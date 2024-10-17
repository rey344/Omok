<?php

// Manages game logic like placing moves, checking for win conditions, and interacting with strategies.
require_once 'Board.php';
require_once 'MoveStrategy.php';
require_once 'RandomStrategy.php';
require_once 'SmartStrategy.php';

// Setup and validate game environment
list($pid, $x, $y) = validateGameEnvironment();

// Load game state and initialize board
$board = loadGameState("../games/$pid.json");

// Process player move and determine outcome
list($isWin, $isDraw, $aiMove) = processMove($board, $x, $y);

// Update game state after server move and save
updateGameState($board, $pid, $aiMove);

// Respond with the updated game state and outcomes
sendResponse($board, $x, $y, $isWin, $isDraw, $aiMove);

/**
 * Validates incoming game parameters and ensures game file exists.
 */
function validateGameEnvironment() {
    // Safely get the GET parameters and ensure they are integers
    $pid = trim($_GET['pid'] ?? '');
    $x = (int) ($_GET['x'] ?? -1);  // Use a default value of -1 for invalid/unset
    $y = (int) ($_GET['y'] ?? -1);  // Use a default value of -1 for invalid/unset

    // Log the parsed values for debugging
    error_log("validateGameEnvironment: PID=$pid, x=$x, y=$y");

    // Check if pid is missing or empty
    if (empty($pid)) {
        respondWithError("Missing game ID (pid)");
    }

    // Check if x and y are set to valid coordinates
    if ($x < 0 || $y < 0) {
        respondWithError("Invalid move coordinates: x=$x, y=$y");
    }

    // Define the path to the game state file and ensure it exists
    $filePath = "../games/$pid.json";
    if (!file_exists($filePath)) {
        respondWithError("Unknown game ID: $pid"); // Unknown game ID
    }

    return [$pid, $x, $y];
}

/**
 * Loads the game state from a JSON file and initializes the board.
 */
function loadGameState($filename) {
    // Check if the file is empty or does not exist
    if (!file_exists($filename) || filesize($filename) === 0) {
        respondWithError(); // Respond with an error if the file is empty or missing
    }

    // Decode the JSON file
    $game_state = json_decode(file_get_contents($filename), true);

    // Validate that the JSON structure is as expected
    if (!isset($_GET['x']) || !isset($_GET['y'])) {
        respondWithError('Missing or invalid move coordinates');
    }


    // Initialize the board
    $board = new Board();
    $board->setBoard($game_state['board']);
    return $board;
}



/**
 * Processes the player move, checks game status, and handles server move.
 */
function processMove($board, $x, $y) {
    error_log("processMove called with coordinates: (x=$x, y=$y)");

    if (!$board->isWithinBounds($x, $y)) {
        error_log("Move out of bounds at coordinates ($x, $y)");
        respondWithError("Invalid move: Spot ($x, $y) is out of bounds");
    }

    if ($board->getBoard()[$x][$y] !== 0) {
        error_log("Cell already occupied at coordinates ($x, $y)");
        respondWithError("Invalid move: Spot ($x, $y) is already occupied");
    }

    error_log("Placing player move at coordinates ($x, $y)");
    $board->placeStone($x, $y, 1); // Player move

    $isWin = $board->checkWin($x, $y, 1);
    $isDraw = $board->checkDraw();

    error_log("Player move at ($x, $y). Win? " . ($isWin ? 'Yes' : 'No') . ", Draw? " . ($isDraw ? 'Yes' : 'No'));

    $strategy = new RandomStrategy($board);
    $aiMove = $strategy->pickPlace();

    if ($aiMove) {
        error_log("Placing AI move at coordinates ({$aiMove['x']}, {$aiMove['y']})");
        $board->placeStone($aiMove['x'], $aiMove['y'], 2);
    } else {
        error_log("No valid moves for AI found.");
    }

    return [$isWin, $isDraw, $aiMove];
}


/**
 * Updates the game state file with the current board status.
 */
function updateGameState($board, $pid, $aiMove) {
    $updatedGameState = [
        'board' => $board->getBoard(),
        'currentPlayer' => $aiMove ? 1 : 0 // Switch back to player if AI moved, otherwise end game
    ];
    file_put_contents("../games/$pid.json", json_encode($updatedGameState));
}

/**
 * Sends the final game state and move acknowledgment to the client.
 */
function sendResponse($board, $x, $y, $isWin, $isDraw, $aiMove) {
    // Log the final coordinates before sending the response
    error_log("Final player coordinates: x=$x, y=$y");
    
    // Ensure $x is not false or unset
    if ($x === false || $y === false) {
        error_log("Invalid coordinate values: x=$x, y=$y. Setting to default values.");
        $x = $x !== false ? $x : -1; // Default invalid value
        $y = $y !== false ? $y : -1; // Default invalid value
    }

    header('Content-Type: application/json');
    echo json_encode([
        'response' => true,
        'ack_move' => [
            'x' => $x,
            'y' => $y,
            'isWin' => $isWin,
            'isDraw' => $isDraw,
            'row' => [] // Update this if a winning row is found
        ],
        'move' => [
            'x' => $aiMove['x'] ?? null,
            'y' => $aiMove['y'] ?? null,
            'isWin' => false, // Check if AI wins
            'isDraw' => false, // Check if game is a draw
            'row' => []
        ]
    ]);
}

/**
 * Sends an error response and stops the script execution.
 */
function respondWithError($message = 'Unknown error') {
    // Log detailed message and backtrace for debugging
    $stackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    error_log("Error: $message\nStack Trace: " . print_r($stackTrace, true));

    // Echo the error message for test output visibility (only when running tests)
    if (defined('IS_TEST_ENVIRONMENT') && IS_TEST_ENVIRONMENT) {
        echo "Error during test P6: $message\n";
    }

    header('Content-Type: application/json');
    echo json_encode(['response' => false, 'reason' => $message]);
    exit;
}


?>
