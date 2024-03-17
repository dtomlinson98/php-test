<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE'); //DELETE deletes
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object
    $category = new Category($db);

    //get raw posted data
    $data = json_decode(file_get_contents('php://input'));

    if (!empty($data->id)) {
        // Set category ID
        $category->id = $data->id;

        // delete category
        if ($category->delete() === true) {
           echo json_encode(array('id' => $data->id));
       } else {
           echo json_encode(array('message' => 'No Categories Found'));
       }
   } else {
       echo json_encode(array('message' => 'Missing Required Parameters'));
   }
?>