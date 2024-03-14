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
        $author->id = $_GET['id'];
        //get single author
        $author->read_single();
    
        //if author exists
        if($author->author != null) {
            //create an array 
            $author_item = array(
                'id'      => $author->id,
                'author'  => $author->author
            );
            echo json_encode($author_item);
        } else {
            //author id Not Found
            echo json_encode(array('message' => 'Author ID Not Found'));
        }
    //if id not given
    } else {
        //fetch all authors
        $result = $author->read();
    
        //check if any authors
        $num = count($result);
        if($num > 0) {
            //create an array to hold the author data
            $authors_arr = array();
            $authors_arr['data'] = $result;
    
            // encode JSON and output
            echo json_encode($authors_arr);
        } else {
            // If no authors found
            echo json_encode(array('message' => 'No Authors Found'));
        }
    }
    ?>