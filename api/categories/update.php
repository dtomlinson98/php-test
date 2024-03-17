<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT'); //PUT updates
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

    //checkign all parameters are given
    if(isset($data->id) && isset($data->category)) {
        // Set id to update
        $category->id = $data->id;
    
        // Set category to update
        $category->category = $data->category;
    
        // Update category
        if($category->update()) {
            // Construct response
            $response = array(
                'id' => $category->id,
                'category' => $category->category 
            );
            echo json_encode($response);
            } else {
                $response = array(
                    'message' => 'Execute Failed',
                );
                echo json_encode($response);
            }
        } else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        }
    
?>