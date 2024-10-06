<?php
require_once 'Board.php';
require_once 'MoveStrategy.php';
require_once 'RandomStrategy.php';
require_once 'SmartStrategy.php';

// Setup and validate game environment
list($pid, $x, $y) = validateGameEnvironment();

// Load game state and initialize board
$board = loadGameState("games/$pid.json");

// Process player move and determine outcome
list($isWin, $isDraw, $aiMove) = processMove($board, $x, $y);

// Update game state after AI move and save
updateGameState($board, $pid, $aiMove);

// Respond with the updated game state and outcomes
sendResponse($board, $x, $y, $isWin, $isDraw, $aiMove);

/**
 * Validates incoming game parameters and ensures game file exists.
 */
function validateGameEnvironment() {
    $pid = $_GET['pid'] ?? '';
    $x = (int)($_GET['x'] ?? -1);
    $y = (int)($_GET['y'] ?? -1);

    if (!file_exists("games/$pid.json")) {
        respondWithError('Unknown game ID');
    }
    return [$pid, $x, $y];
}

/**
 * Loads the game state from a JSON file and initializes the board.
 */
function loadGameState($filename) {
    $game_state = json_decode(file_get_contents($filename), true);
    $board = new Board();
    $board->setBoard($game_state['board']);
    return $board;
}

/**
 * Processes the player move, checks game status, and handles AI move.
 */
function processMove($board, $x, $y) {
    if (!$board->isMoveValid($x, $y)) {
        respondWithError('Invalid move');
    }
    $board->placeStone($x, $y, 1); // Player move
    $isWin = $board->checkWin($x, $y);
    $isDraw = $board->checkDraw();

    // Select strategy based on game state or configuration
    $strategy = new RandomStrategy($board);
    $aiMove = $strategy->pickPlace();
    if ($aiMove) {
        $board->placeStone($aiMove['x'], $aiMove['y'], 2);
    }

    return [$isWin, $isDraw, $aiMove];
}

/**
 * Updates the game state file with current board status.
 */
function updateGameState($board, $pid, $aiMove) {
    $updatedGameState = $board->getBoard();
    file_put_contents("games/$pid.json", json_encode(['board' => $updatedGameState]));
}

/**
 * Sends the final game state and move acknowledgment to the client.
 */
function sendResponse($board, $x, $y, $isWin, $isDraw, $aiMove) {
    header('Content-Type: application/json');
    echo json_encode([
        'response' => true,
        'ack_move' => ['x' => $x, 'y' => $y, 'isWin' => $isWin, 'isDraw' => $isDraw, 'row' => []],
        'move' => ['x' => $aiMove['x'] ?? null, 'y' => $aiMove['y'] ?? null, 'isWin' => false, 'isDraw' => false, 'row' => []]
    ]);
}

/**
 * Responds with an error message and halts further script execution.
 */
function respondWithError($message) {
    echo json_encode(['response' => false, 'reason' => $message]);
    exit;
}
?>
