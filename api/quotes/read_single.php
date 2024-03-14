<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quotes.php';

//instantiate DB and connect
$database = new Database();
$db = $database->connect();

//instantiate quote object
$quote = new Quote($db);

//if id set...
if(isset($_GET['id'])) {
    $quote->id = $_GET['id'];
    //get the single quote
    $quoteFound = $quote->read_single();

    //create an array to hold the quote data
    if ($quoteFound) {
        // Create an array to hold the quote data
        $quote_item = array(
            'id'      => $quoteFound['id'],
            'quote'   => $quoteFound['quote'],
            'author'  => $quoteFound['author'],
            'category'=> $quote_data['category']
        );
        echo json_encode($quote_item);
    } else {
        // Respond with Quote ID Not Found
        echo json_encode(array('message' => 'Quote ID Not Found'));
    }
//if id not given
} else {
    echo json_encode(array('message' => 'No Quotes Found'));
}
?>
