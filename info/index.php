<?php

/**
 * Provides game-related information like board size and available strategies.
 * 
 * Retrieves game information for Omok, including board size and available strategies.
 *
 * This script prepares and sends game-related data in JSON format to the client.
 * It provides details such as the size of the game board and the strategies that can be used.
 */

// Function to fetch and prepare game information
function fetchGameInfo() {
    return [
        'size' => 15, // Define the size of the board, 15x15 is a typical size for Omok.
        'strategies' => ['Smart', 'Random'] // List the available game strategies.
    ];
}

// Function to send a JSON response to the client
function sendJsonResponse($data) {
    header('Content-Type: application/json'); // Set the content type of the response to JSON.
    echo json_encode($data); // Encode the data array to a JSON string and output it.
}

// Main execution
$info = fetchGameInfo(); // Get the game information.
sendJsonResponse($info); // Send the information as a JSON response.

?>
