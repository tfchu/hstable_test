<?php
require 'vendor/autoload.php';

$user = 'test';
$pass = '123';
# mongodb://test:123@127.0.0.1/test_db
$client = new MongoDB\Client("mongodb://${user}:${pass}@127.0.0.1/test_db");

try
{
	$db = $client->listDatabases();
    //print_r($db);
    //echo "<br>";
}
catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e)
{
	echo "error";
}

//$db = $connection->selectDB('test_db');
$collection = $client->test_db->results;

// PHP-mongodb: https://www.php.net/manual/en/mongodb.tutorial.library.php
// CRUD: https://docs.mongodb.com/php-library/master/reference/class/MongoDBCollection/
// (C) insert new document
//$result = $collection->insertOne(['name' => 'tony', 'age' => 24, 'status' => 'B', 'email' => 'tc@tc.com', 'groups' => ["editor"]]);
//echo "Inserted with Object ID '{$result->getInsertedId()}'";

// (R) find all document
$results = $collection->find();
/* $results = $collection->find(
    [
        '_id' => 1, 
        'project' => 'Aquarius B0'
    ]
); */
//echo json_encode($results);

/* // (U) update
$updated_result = $collection->updateOne(
    ['_id' => 1], 
    ['$set' => ['name' => 'new name']]
);

// (D) delete
$deleted_result = $collection->deleteOne(
    ['_id' => 1]
);
 */
//print_r($results);

$a = array();

foreach ($results as $result) {
    //echo $result['_id'], ' ', $result['project'], ' ', $result['version'], ' ', $result['fw'], ' ', $result['tester'], '<br>';
    array_push($a, array($result['_id'], $result['project'], $result['version'], $result['fw'], $result['tester']));
}

echo json_response(200, array('data' => $a));

//phpinfo(); 

// https://gist.github.com/james2doyle/33794328675a6c88edd6
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
