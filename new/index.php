<?php

// Handles creating a new game and initializing the board state.

// Include the Board class
require_once '../play/Board.php';

// Define allowed strategies in a configuration or as constants.
define('ALLOWED_STRATEGIES', ['Smart', 'Random']);

// Main logic
handleNewGameRequest();

/**
 * Handles the creation of a new game by validating the strategy and generating a game ID.
 */
function handleNewGameRequest() {
    $strategy = $_GET['strategy'] ?? '';

    if (!isValidStrategy($strategy)) {
        respondWithError(); // If no or invalid strategy, respond with a standard error.
    }

    $pid = generateUniqueId();

    // Create the initial game state file for this game
    createInitialGameState($pid, $strategy); // Pass the strategy to use

    sendResponse($pid);
}

/**
 * Validates if the provided strategy is allowed.
 */
function isValidStrategy($strategy) {
    return in_array($strategy, ALLOWED_STRATEGIES);
}

/**
 * Generates a unique identifier for the new game session.
 */
function generateUniqueId() {
    return uniqid();
}

/**
 * Creates the initial game state file using the Board class and saves it as a JSON file.
 */
function createInitialGameState($pid, $strategy) {
    // Define the path to the new game state file in the 'games' directory
    $filePath = "../games/$pid.json";

    // Create a new Board object and initialize it
    $board = new Board();
    $board->initializeBoard(); // This sets up a 15x15 empty board

    // Create the initial game state using the Board object's state
    $initialGameState = [
        'board' => $board->getBoard(), // Get the empty board state from the Board object
        'currentPlayer' => 1,          // Set the current player to 1 (Player's turn)
        'strategy' => $strategy        // Use the strategy provided in the request
    ];

    // Save the initial game state to the JSON file
    file_put_contents($filePath, json_encode($initialGameState));
}

/**
 * Sends a successful response with the unique game ID.
 */
function sendResponse($pid) {
    header('Content-Type: application/json');
    echo json_encode([
        'response' => true,
        'pid' => $pid
    ]);
}

/**
 * Sends a standard error response for missing or invalid strategy.
 */
function respondWithError() {
    header('Content-Type: application/json');
    echo json_encode(['response' => false]); // Return false response as required
    exit;
}

?>
