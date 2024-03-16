<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate category object
    $category = new Category($db);
    
    if(isset($_GET['id'])) {
        include_once 'read_single.php';
    //if id not given
    } else {
        //fetch all categories
        $result = $category->read();
    
        //check if any categories
        $num = count($result);
        if($num > 0) {
            //create an array to hold the category data
            echo json_encode($result);
        } else {
            //if no categories in database
            echo json_encode(array('message' => 'category_id Not Found - read'));
        }
    }
?>
