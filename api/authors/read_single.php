<?php
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog author object
    $author = new Author($db);
    
    //set id from url
    $author->id = $_GET['id'];
    //get single author
    $author->read_single();

    //set single author
    $singleAuthor = $author->author;
    
    //if author exists
    if($singleAuthor != null) {
        //create an array 
        $author_item = array(
            'id'      => $author->id,
            'author'  => $singleAuthor
        );
        echo json_encode($author_item);
    } else {
        //author id Not Found
        echo json_encode(array('message' => 'author_id Not Found'));
    }
    ?>
