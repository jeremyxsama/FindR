<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate product object
include_once '../objects/restaurants.php';

$database = new Database();
$db = $database->getConnection();

$restaurants = new Restaurants($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->name) &&
    !empty($data->address) &&
    !empty($data->city) &&
    !empty($data->about) &&
    !empty($data->website) &&
    !empty($data->image_url)
) {

    // set restaurants property values
    $restaurants->name = $data->name;
    $restaurants->address = $data->address;
    $restaurants->city = $data->city;
    $restaurants->about = $data->about;
    $restaurants->website = $data->website;
    $restaurants->image_url = $data->image_url;

    // create the restaurants
    if ($restaurants->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Restaurant was added successfully."));
    }

    // if unable to create the restaurants, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to add restaurant."));
    }
}

// tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to add restaurant. Data is incomplete."));
}