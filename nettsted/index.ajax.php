<?php
require_once("php/classes/Database.php");

/**
 * Serves AJAX requests from index.php
 */

$dbApi = new Database();

/**
 * If we have hotelID in GET, print the hotels room types as a json. *
 */
if(isset($_GET["hotelID"])) {
    $roomTypes = $dbApi->getRoomTypes($_GET["hotelID"]);

    $names = array();
    foreach($roomTypes as $roomType) {
        array_push($names, $roomType->getName());
    }

   print(json_encode($names));
} else {
    exit("hotelID not in GET-array");
}