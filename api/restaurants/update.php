<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/restaurants.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare rest$restaurants object
$restaurants = new Restaurants($db);

// get id of rest$restaurants to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of rest$restaurants to be edited
$restaurants->id = $data->id;

// set rest$restaurants property values
$restaurants->name = $data->name;
$restaurants->address = $data->address;
$restaurants->city = $data->city;
$restaurants->about = $data->about;
$restaurants->website = $data->website;
$restaurants->image_url = $data->image_url;

// update the rest$restaurants
if ($restaurants->update()) {

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "restaurant was updated."));
}

// if unable to update the rest$restaurants, tell the user
else {

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to update restaurants."));
}