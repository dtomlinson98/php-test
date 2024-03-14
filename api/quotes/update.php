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
    
    //checking if all parameters are provided
    if(isset($data->id) && isset($data->quote) && isset($data->author_id) && isset($data->category_id)) {
        //set id to update
        $quote->id = $data->id;
        //set id to update
        $quote->id = $data->id;
    
        $quote->id              = $data->id;
        $quote->quote           = $data->quote;
        $quote->author_id       = $data->author_id;
        $quote->category_id     = $data->category_id;
    
        //update quote
        if($quote->update()) {
            //use read_single to read updated quote
            $updatedQuote = $quote->read_single();
            
            //check if quote was found
            if($updatedQuote) {
                // response
                $response = array(
                    'message' => 'Quote Updated',
                    'quote' => $updatedQuote
                );
                echo json_encode($response);
            } else {
                echo json_encode(array('message' => 'Quote Updated'));
            }
        } else {
            echo json_encode(array('message' => 'Quote Updated'));
        }
    } else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
?>