<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/restaurants.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$restaurants = new Restaurants($db);

// set ID property of record to read
$restaurants->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of restaurants to be edited
$restaurants->readOne();

if ($restaurants->name != null) {
    // create array
    $restaurants_arr = array(
        "id" => $restaurants->id,
        "name" => $restaurants->name,
        "address" => $restaurants->address,
        "city" => $restaurants->city,
        "about" => $restaurants->about,
        "website" => $restaurants->website,
        "image_url" => $restaurants->image_url
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($restaurants_arr);
} else {
    // set response code - 404 Not found
    http_response_code(404);
    // tell the user restaurants does not exist
    echo json_encode(array("message" => "Restaurant Not Found"));
}