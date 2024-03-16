<?php

include_once '../../config/Database.php';
include_once '../../models/Quotes.php';

// Instantiate DB and connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// set id from url
$quote->id = $_GET['id'];


if($quote->quoteExists()) {
    // run read_single
    $quoteData = $quote->read_single();
    
    echo json_encode($quoteData);
} else {
    echo json_encode(array('message' => 'No Quotes Found'));
}
?>
