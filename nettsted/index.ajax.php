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
                          $dbApi->stringToDate($_GET["startDate"]),
                          $dbApi->stringToDate($_GET["endDate"]));
            break;

        case "addBook":
            addNewOrderAndBooking(  $dbApi,
                                    $_GET["email"],
                                    $_GET["hotelID"],
                                    $_GET["roomTypeID"],
                                    $_GET["startDate"],
                                    $_GET["endDate"]);
            break;

        case "validateDates":
            isDatesValid($dbApi->stringToDate($_GET["startDateString"]), $dbApi->stringToDate($_GET["endDateString"]));
            break;

        default:
            exit("@ Default in switch: Shit went bad!");
            break;
    }
}
/* -------------------- SCRIPT END -------------------- */

/**
 * Checks if the dates come after today, and that the end date is later than the start date.
 *
 * @param DateTime $startDate
 * @param DateTime $endDate
 * @return boolean Returns true if the dates are valid, false otherwise.
 */
function isDatesValid(DateTime $startDate, DateTime $endDate) {
    $startTS = $startDate->getTimestamp();
    $endTS = $endDate->getTimestamp();
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    $todayTS = $today->getTimestamp();

    $json = array();
    $json["isValid"] = true;

    // Is start date before today?
    if($startTS < $todayTS) {
        // If so, thats bad.
        $json["isValid"] = false;
        $json["message"] = "Startdato må være >= dagens dato";
    }

    // Is end date before, or on same day as start date?
    if($endTS <= $startTS) {
        // If so, thats bad.
        $json["isValid"] = false;
        $json["message"] = "Sluttdatoen må være etter startdatoen!";
    }

    if($json["isValid"] === false) {
        print(json_encode($json));
    } else {
        $json["isValid"] = true;
        $json["message"] = "";
        print(json_encode($json));
    }
}

/**
 * Validates an email
 */
function isEmailValid($emailString) {
    if(filter_var($emailString, FILTER_VALIDATE_EMAIL) === false) {
        return false;
    } else {
        return true;
    }
}

/**
 * Add a order and booking, then returns the reference number and success/failure in a JSON
 */
function addNewOrderAndBooking(Database $dbApi, $email, $hotelID, $roomTypeID, $startDate, $endDate) {
    $json = array();

    // Check if the email is valid
    if(isEmailValid($email)) {
        // Compute reference
        $orderReference = md5($email);

        // Make DateTimes
        $dt_startDate = DateTime::createFromFormat("d.m.Y", $startDate);
        $dt_startDate->setTime(0, 0, 0);
        $dt_endDate = DateTime::createFromFormat("d.m.Y", $endDate);
        $dt_endDate->setTime(0, 0, 0);

        $isSuccess = $dbApi->addBooking($orderReference, $hotelID, $roomTypeID, $dt_startDate, $dt_endDate);

        $json["isSuccess"] = $isSuccess;
        $json["refNr"] = $orderReference;
        $json["message"] = "";
    } else {
        $json["isSuccess"] = false;
        $json["message"] = "Vennligst tast inn en gyldig epost.";
    }

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
 * Prints showSearchResults-data JSON
 */
function getSearchJSON(Database $dbApi, $hotelID, $roomTypeID, DateTime $startDate, DateTime $endDate) {
    $searchData = array();

    $numOfAvailableRooms = $dbApi->getNumOfAvailableRooms($hotelID, $roomTypeID, $startDate, $endDate);

    if($numOfAvailableRooms < 0) {
        $searchData["numRooms"] = 0;
    } else {
        $searchData["numRooms"] = $numOfAvailableRooms;
    }

    /** Hotel image url and description */
    $hotel = $dbApi->getHotel($hotelID);
    $searchData["hotelImageURL"] = $hotel->getImageURL();
    $searchData["hotelDescription"] = $hotel->getDescription();

    /** Room type image url, description, price and number of beds */
    $roomType = $dbApi->getRoomType($roomTypeID, $hotelID);
    $searchData["roomTypeImageURL"] = $roomType->getImageURL();
    $searchData["roomTypeDescription"] = $roomType->getDescription();
    $searchData["roomTypePrice"] = $roomType->getPrice();
    $searchData["roomTypeNumOfBeds"] = $roomType->getNumOfBeds();

    print(json_encode($searchData));
}