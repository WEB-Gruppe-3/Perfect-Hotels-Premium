<?php
require_once("php/classes/Database.php");

$dbApi = new Database();

if(isset($_GET["requestedData"])) {

    switch($_GET["requestedData"]) {
        case "getNumAvailRooms":
            printNumOfAvailRoomsJSON(   $dbApi,
                                        $_GET["hotelID"],
                                        $_GET["roomTypeID"],
                                        $_GET["startDate"],
                                        $_GET["endDate"]);
            break;

        case "dateCheck":
            isDatesValid(   $_GET["startDate"],
                            $_GET["endDate"]);
            break;

        default:
            exit("SHIT WENT BAD IN editorder.ajax.php, reached default in switch!!");
            break;
    }
}

/**
 * Prints the number of available rooms in a fucking JSON
 */
function printNumOfAvailRoomsJSON($dbApi, $hotelID, $roomTypeID, $startDate, $endDate) {
    // The dates we recieved here are like this:
    // år-måned-dag

    // Lets make some date objects
    $objDateStart = DateTime::createFromFormat("Y-m-d", $startDate);
    $objDateStart->setTime(0, 0, 0);
    $objDateEnd = DateTime::createFromFormat("Y-m-d", $endDate);
    $objDateEnd->setTime(0, 0, 0);

    $numAvailRooms = $dbApi->getNumOfAvailableRooms($hotelID, $roomTypeID, $objDateStart, $objDateEnd);

    $json = array("numRooms" => $numAvailRooms);

    print(json_encode($json));
}

/**
 * Checks if the dates come after today, and that the end date is later than the start date.
 */
function isDatesValid($startDateString, $endDateString) {

    // The date strings has this format: YYYY-MM-DD
    $startDate = DateTime::createFromFormat("Y-m-d", $startDateString);
    $endDate = DateTime::createFromFormat("Y-m-d", $endDateString);
    $startDate->setTime(0, 0, 0);
    $endDate->setTime(0, 0, 0);

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