<?php
// check if a strategy is specified and is one of the allowed values
// this prevents clients from starting a game without a valid strategy
if(!isset($_GET['strategy']) ||!in_array($_GET('strategy'], ['Smart', 'Random'])) {
    // respond with an error if no valid strategy is specified
    // this ensures the client knows the cause of the error and can handle it accordingly
    echo json_encode(['responce' => false, 'reason' => "Invalid or not strategy specified']);
    exit; // Stop further script execution as there's an error
}

// Generate a unique game identifier using PHP's uniqid function
// uniqid() is based on the microtime, providing a simple mechanism for unique ID's
$pid = uniqid();

// prepare the responce array with the status and the unique game ID
$responce = [
    'response' => true, // indicates the request was successful
    'pid' => $pid  // provides the client with the ID identifier for future requests
];

// set the content type of the responce to application/json
// this header informs the client that the returned content should be treated as JSON,
// making it easier for the client-side JavaScript to parse it
header('Content-Type: application/json');

// encode the responce array to JSON string and output it
// json_encode converts the PHP array into a JSON-formatteds string for client consumption
echo json_encode($response);
?>
