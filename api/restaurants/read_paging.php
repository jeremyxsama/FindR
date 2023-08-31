<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/restaurants.php';

// utilities
$utilities = new Utilities();

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$restaurants = new Restaurants($db);

// query products
$stmt = $restaurants->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    // products array
    $restaurants_arr = array();
    $restaurants_arr["records"] = array();
    $restaurants_arr["paging"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $restaurants_item = array(
            "id" => $id,
            "name" => $name,
            "address" => html_entity_decode($address),
            "city" => $city,
            "about" => $about,
            "website" => $website,
            "image_url" => $image_url
        );

        array_push($restaurants_arr["records"], $restaurants_item);
    }


    // include paging
    $total_rows = $restaurants->count();
    $page_url = "{$home_url}restaurants/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $restaurants_arr["paging"] = $paging;

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($restaurants_arr);
} else {

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user products does not exist
    echo json_encode(
        array("message" => "No restaurants found.")
    );
}