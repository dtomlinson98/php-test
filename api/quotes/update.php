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
    

    //set properties to update
    $quote->id              = $data->id;
    $quote->quote           = $data->quote;
    $quote->author_id       = $data->author_id;
    $quote->category_id     = $data->category_id;
    
    //update quote
    $response = $quote->update();
        
    //output JSON
    echo json_encode($response);
     
        
  
?>