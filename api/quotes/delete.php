<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE'); //DELETE deletes
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

    if (!empty($data->id)) {
        // Set quote ID
        $quote->id = $data->id;

        // delete quote
        if ($quote->delete() === true) {
           echo json_encode(array('message' => 'Quote deleted', 'id' => $data->id));
       } else {
           echo json_encode(array('message' => 'Quote not deleted'));
       }
   } else {
       echo json_encode(array('error' => 'Missing Required Parameters'));
   }
?>