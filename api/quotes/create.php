<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    

    include_once '../../config/Database.php';
    include_once '../../models/Quotes.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object
    $quote = new Quote($db);

    //get raw posted data
    $data = json_decode(file_get_contents('php://input'));

    //assign data
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    $response = $quote->create();

    //create post
    if ($response) {
        echo json_encode($response);
    } else {
        echo json_encode(array('message' => 'Quote not created'));
    }
?>