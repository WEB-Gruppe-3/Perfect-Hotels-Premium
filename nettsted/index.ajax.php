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

        case "numOfAvailableRooms":
            printNumOfAvailableRooms( $dbApi,
                                      $_GET["hotelID"],
                                      $_GET["roomTypeID"],
                                      stringToDate($_GET["startDate"]),
                                      stringToDate($_GET["endDate"]) );
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

        $ids = array();
        $names = array();
        foreach($roomTypes as $roomType) {
            array_push($ids, $roomType->getID());
            array_push($names, $roomType->getName());
        }

        $json = array($ids, $names);

        print(json_encode($json));
    } else {
        exit("hotelID not in GET-array");
    }
}

/**
 * Prints the number of available rooms during based upon the search parameters.
 */
function printNumOfAvailableRooms(Database $dbApi, $hotelID, $roomTypeID, DateTime $startDate, DateTime $endDate) {
    $searchStartTs = $startDate->getTimestamp();
    $searchEndTs = $endDate->getTimestamp();

    // Get all non-expired bookings
    $bookings = $dbApi->getActiveBookings($hotelID, $roomTypeID);

    // Count how many bookings already exists in that timeframe.
    $numOfBusyBookings = 0;
    foreach($bookings as $book) {
        $bookStartDate = $book->getStartDate();
        $bookEndDate = $book->getEndDate();

        $bookStartTs = $bookStartDate->getTimestamp();
        $bookEndTs = $bookEndDate->getTimestamp();

        // Now, lets find out if this booking collides with the desired search.
        // To avoid collision, the following must be true:
        // - The search startDate and endDate must be before the booked startDate,
        //   OR the search startDate must be after the booked endDate.

        // Check if the current booking collides with search parameters.
        if(false === (($searchStartTs < $bookStartTs && $searchEndTs < $bookStartTs) ||
            ($searchStartTs > $bookEndTs)) ) {
            // If so, increment the number of busy bookings.
            $numOfBusyBookings++;
        }
    }

    // Get the total number of rooms for this hotel's roomtype.
    $rooms = $dbApi->getRooms($hotelID, $roomTypeID);
    $numOfRooms = count($rooms);

    // Subtract the taken rooms from the total number of rooms to find the available number of rooms
    $numOfAvailableRooms = $numOfRooms - $numOfBusyBookings;

    // Deliver the JSON
    print(json_encode(array($numOfAvailableRooms)));
}

/**
 * Helper function to convert string dates from JS to DateTime
 */
function stringToDate($dateString) { // Format: 03.06.2015
    // Replace . with -
    $replace = str_replace(".", "-", $dateString);

    // Now looks like this 03-06-2015 (DD-MM-YYYY)
    // Make a DateTime
    $date = date_create_from_format("d-m-Y", $replace);

    return $date;
}