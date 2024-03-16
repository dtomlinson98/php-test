<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog author object
    $author = new Author($db);
    
    if(isset($_GET['id'])) {
        include_once 'read_single.php';
    //if id not given
    } else {
        //fetch all authors
        $result = $author->read();
    
        //check if any authors
        $num = count($result);
        if($num > 0) {
            //create an array to hold the author data
            echo json_encode($result);
        } else {
            //if no authors in datbase
            echo json_encode(array('message' => 'Authors Database Empty'));
        }
    }
    ?>