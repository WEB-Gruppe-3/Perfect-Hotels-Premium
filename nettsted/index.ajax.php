<?php
require_once("php/classes/Database.php");

/**
 * Serves AJAX requests from index.php
 */

$dbApi = new Database();

// Decide what method to run based on data from request
if(isset($_GET["requestedData"])) {

    switch($_GET["requestedData"]) {
        case "roomTypes":
            printRoomTypes($dbApi, $_GET["hotelID"]);
            break;

        case "availableRooms":
            printAvailableRooms($dbApi, $_GET["hotelID"], $_GET["roomTypeID"], $_GET["startDate"], $_GET["endDate"]);
            break;

        default:
            exit("@ Default in switch: Shit went bad!");
            break;
    }
}


/**
 * Print room types for a hotel
 */
function printRoomTypes($dbApi, $hotelID) {
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
}

/**
 * Prints TODO
 */
function printAvailableRooms(Database $dbApi, $hotelID, $roomTypeID, $startDate, $endDate) {
    // Find the available rooms between the date interval.
    // TODO do dis
    //print();

}