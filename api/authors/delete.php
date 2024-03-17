<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE'); //DELETE deletes
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

     //author object
     $author = new Author($db);

     //get author id
     $data = json_decode(file_get_contents("php://input"));
 
     // check if author ID is provided
     if (!empty($data->id)) {
         // Set author ID
         $author->id = $data->id;
 
         // delete author
         if ($author->delete() === true) {
            echo json_encode(array('id' => $data->id));
        } else {
            echo json_encode(array('message' => 'No Author Found'));
        }
    } else {
        echo json_encode(array('error' => 'Author ID not provided'));
    }
 ?>