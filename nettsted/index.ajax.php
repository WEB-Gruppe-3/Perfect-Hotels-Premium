<?php
/**
 * Serves AJAX requests from index.php
 */

require_once("php/classes/Database.php");

$dbApi = new Database();

/* -------------------- SCRIPT START -------------------- */
// Decide what method to run based on data from request
if(isset($_GET["requestedData"])) {

    switch($_GET["requestedData"]) {
        case "roomTypes":
            printRoomTypes($dbApi, $_GET["hotelID"]);
            break;

        case "searchData":
            getSearchJSON($dbApi,
                          $_GET["hotelID"],
                          $_GET["roomTypeID"],
                          stringToDate($_GET["startDate"]),
                          stringToDate($_GET["endDate"]));
            break;

        case "addBook":
            addNewOrderAndBooking(  $dbApi,
                                    $_GET["email"],
                                    $_GET["hotelID"],
                                    $_GET["roomTypeID"],
                                    $_GET["startDate"],
                                    $_GET["endDate"]);
            break;

        default:
            exit("@ Default in switch: Shit went bad!");
            break;
    }
}
/* -------------------- SCRIPT END -------------------- */

/**
 * Add a order and booking, then returns the reference number and success/failure in a JSON
 */
function addNewOrderAndBooking(Database $dbApi, $email, $hotelID, $roomTypeID, $startDate, $endDate) {
    // Compute reference
    $orderReference = md5($email);

    // Make DateTimes
    $dt_startDate = DateTime::createFromFormat("d.m.Y", $startDate);
    $dt_endDate = DateTime::createFromFormat("d.m.Y", $endDate);

    // $orderReference, $hotelID, $roomTypeID, DateTime $startDate, DateTime $endDate
    $isSuccess = $dbApi->addBooking($orderReference, $hotelID, $roomTypeID, $dt_startDate, $dt_endDate);

    $json = array();
    $json["isSuccess"] = $isSuccess;
    $json["refNr"] = $orderReference;

    print(json_encode($json));
}

/**
 * Print room types for a hotel
 */
function printRoomTypes(Database $dbApi, $hotelID) {
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
 * Prints search-data JSON
 */
function getSearchJSON(Database $dbApi, $hotelID, $roomTypeID, DateTime $startDate, DateTime $endDate) {
    $searchData = array();

    /** Number of available rooms */
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

    // Add the num of avail rooms to search data.
    $numOfAvailableRooms = $numOfRooms - $numOfBusyBookings;
    $searchData["numRooms"] = $numOfAvailableRooms;

    /** Hotel image url and description */
    $hotel = $dbApi->getHotel($hotelID);
    $searchData["hotelImageURL"] = $hotel->getImageURL();
    $searchData["hotelDescription"] = $hotel->getDescription();

    /** Room type image url and description */
    $roomType = $dbApi->getRoomType($roomTypeID, $hotelID);
    $searchData["roomTypeImageURL"] = $roomType->getImageURL();
    $searchData["roomTypeDescription"] = $roomType->getDescription();

    print(json_encode($searchData));
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