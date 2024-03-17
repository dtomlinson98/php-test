<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT'); //PUT updates
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
    
    //check for id
    if (!isset($data->id)) {
        
        echo json_encode(array("message" => "Missing Required Parameters"));
        exit;
    }

    //set id
    $quote->id = $data->id;

    // if property wasn't given, missing property will be set null
    $quote->quote = isset($data->quote) ? $data->quote : null;
    $quote->author_id = isset($data->author_id) ? $data->author_id : null;
    $quote->category_id = isset($data->category_id) ? $data->category_id : null;
    
    //update quote
    $response = $quote->update();
        
    //output JSON
    echo json_encode($response);
     
        
  
?>