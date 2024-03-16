<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quotes.php';

//instantiate DB and connect
$database = new Database();
$db = $database->connect();

//instantiate quote object
$quote = new Quote($db);

//if id set...
if(isset($_GET['id'])) {
    include_once 'read_single.php';

//if id not given
} else {
    //fetch all quotes
    $result = $quote->read();

    $num = $result->rowCount();
    if($num > 0) {
        //create an array to hold the quote data
        $quotes_arr = array();

        //loop through each column
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id'       => $id,
                'quote'    => $quote,
                'author'   => $author,
                'category' => $category
            );

            // push to array
            array_push($quotes_arr, $quote_item);
        }

        echo json_encode($quotes_arr);
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
}
?>
