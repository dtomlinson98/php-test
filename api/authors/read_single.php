<?php
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog author object
    $author = new Author($db);
    
    $author->id = isset($_GET['id']) ? $_GET['id'] : die();

    //single author
    if ($author->read_single()) {
        //author found
        $authorItem = array(
            'id'      => $author->id,
            'author'  => $author->author
        );
        echo json_encode($authorItem);
    } else {
        //author not found
        echo json_encode(array('message' => 'author_id Not Found.'));
    }
    ?>
