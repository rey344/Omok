<?php
// retrieve game identifiers and move coordinates from the query string
$pid = $_GET['pid']; // pid stands for game identifier, unique to each game session
$x = $_GET['x']; // 'x' is the horizontal coordinate of the move on the game board
$y = $_GET['y']; // 'y' is the vertical coordinate of the move on the game board

// Check if the game file for the current session exist
if (!file_exist("games/$pid.json")) {
    // if game file does not exist, respond with an error
    echo json_encode(['responce' => false, 'reason' => 'Unknown game ID']);
    exit;  // stop further script execution since there's no valid game session
}

// load the game state from the JSON file
$game_state = json_decode(file_get_contents("games/$pid.json"), true);
// json_decode converts the JSON string stored in the file back into an associative array

// process the move and and update the game state
// here you would include logic to update the position with $x, $y, check for the legality of the move
// and then determine if the move results in a win a draw
// (pseudo-code and logic for these operations should be implemented here)

// save the updated game state back to the file
// json_encode converts the updated game state array back into a JSON string
file_put_contents("games/$pid.json", json_encode($game_state));

// set the content type of the responce to application/json
// this header informs the client that the responce format is JSON, ensuring proper handling on the client side
header('Content-Type: application/json');

// send the updated game state and move acknowledgment back to the client
echo json_encode([
    'responce' => true,
    'ack_move' => [
        'x' => $x,
        'y' => $y,
        'isWin' => false,  // example place holder, adjust based on the actual game logic
        'isDraw' => false,  // example place holder, adjust based on actual game logic
        'row' => []  // example place holder, this would show winning row if isWin is true
    ],
    'move' => [
        'x' => $x + 1,  // example next move by the computer, adjust according to your strategy
        'y' => $y,
        'isWin' => false,
        'isDraw' => false,
        'row' => []
    ]
]);
?>
