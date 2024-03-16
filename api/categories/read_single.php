<?php
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate category object
    $category = new Category($db);
    
    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get single category
    if ($category->read_single()) {
        // category found
        $category_item = array(
            'id'      => $category->id,
            'category'  => $category->category
        );
        echo json_encode($category_item);
    } else {
        //category not found
        echo json_encode(array('message' => 'category_id Not Found.'));
    }
?>
