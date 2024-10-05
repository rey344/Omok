<?php
// preparing an array with game information
$info = [
    'size' => 15, // size of the board
    'strategies' => ['Smart', 'Random'] // available strategies
];

// set the content- type header to application/json
// this tells the client to expect JSON formatted data as a responce.
header('Content-Type: application/json');

// convert the #info array into a JSON string and send it to the client
echo json_encode($info);
?>
