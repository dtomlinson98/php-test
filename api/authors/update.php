<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT'); //PUT updates
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object
    $author = new Author($db);

    //get raw posted data
    $data = json_decode(file_get_contents('php://input'));

    //checkign all parameters are given
    if(isset($data->id) && isset($data->author)) {
        // Set id to update
        $author->id = $data->id;
    
        // Set author to update
        $author->author = $data->author;
    
        // if parameters are given and update returns true.. 
        if($author->update()) {
            //response
            $response = array(
                'message' => 'Author Updated',
                'id' => $author->id,
                'author' => $author->author
                
            );
            echo json_encode($response);
            }
        } else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        }
    
?>