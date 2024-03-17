<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// include database and author model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// instantiate DB and connect
$database = new Database();
$db = $database->connect();

// instantiate DB and author object
$author = new Author($db);

// get raw posted data
$data = json_decode(file_get_contents('php://input'));

// assign data
$author->author = isset($data->author) ? $data->author : null;

// create author and get the response
$response = $author->create();

// Check if the response is successful and return it
if ($response) {
    echo json_encode($response);
} else {
    echo json_encode(array('message' => 'author_id Not Found'));
}
?>
