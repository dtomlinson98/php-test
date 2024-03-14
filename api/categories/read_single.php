<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog category object
    $category = new Category($db);
    
    if(isset($_GET['id'])) {
        $category->id = $_GET['id'];
        //get single category
        $category->read_single();
    
        //if categories exists
        if($category->category != null) {
            //create an array 
            $category_item = array(
                'id'      => $category->id,
                'category'  => $category->category
            );
            echo json_encode($category_item);
        } else {
            //category id Not Found
            echo json_encode(array('message' => 'Category ID Not Found'));
        }
    //if id not given
    } else {
        //fetch all category
        $result = $category->read();
    
        //check if any category
        $num = count($result);
        if($num > 0) {
            //create an array to hold the category data
            $categories_arr = array();
            $categories_arr['data'] = $result;
    
            // encode JSON and output
            echo json_encode($categories_arr);
        } else {
            // If no categories found
            echo json_encode(array('message' => 'No Categories Found'));
        }
    }
    ?>