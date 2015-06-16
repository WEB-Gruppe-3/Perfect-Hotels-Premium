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