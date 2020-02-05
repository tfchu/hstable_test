<?php
require 'vendor/autoload.php';

// echo "<pre>";
// print_r($_POST['data']);
// echo "</pre>";

$results = $_POST['data'];

// connect
$user = 'test';
$pass = '123';
# mongodb://test:123@127.0.0.1/test_db
$client = new MongoDB\Client("mongodb://${user}:${pass}@127.0.0.1/test_db");

// get results collection
$collection = $client->test_db->results;

// update results
// $updated_result = $collection->updateOne(
//     ['_id' => 1], 
//     ['$set' => ['name' => 'new name']]
// );

$a = array();

// need to add: do updateOne() if id is found
foreach ($results as $result) {
    $result = $collection->insertOne(['project' => $result[1], 'version' => $result[2], 'fw' => $result[3], 'tester' => $result[4]]);
    array_push($a, $result->getInsertedId());    
}

echo json_response(200, array('data' => $a));

function json_response($code = 200, $response = null)
{
    // clear the old headers
    header_remove();
    // set the actual code
    http_response_code($code);
    // set the header to make sure cache is forced
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    // treat this as json
    header('Content-Type: application/json');
    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
        );
    // ok, validation error, or failure
    header('Status: '.$status[$code]);
    // return the encoded json
    return json_encode(array(
        'status' => $code < 300, // success or not?
        'response' => $response
        ));
}

?>