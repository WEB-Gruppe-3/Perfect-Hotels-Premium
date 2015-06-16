<?php

if(isset($_GET["request"])) {

    switch($_GET["request"]) {
        case "logOut":
            logOut($_GET["sessionID"]);
            break;

        case "dateCheck":
            isDatesValid(   $_GET["startDateString"],
                            $_GET["endDateString"]);
            break;

        default:
            exit("Shit happened, reached default in vedlikehold/index.ajax.php");
            break;
    }
}

/**
 * Destroys the current session
 */
function logOut($sessionID) {
    // Start a session
    session_start();

    // Change it's ID to the logged in user's session ID
    session_id($sessionID);

    // Destroy the session
    $isDestroyed = session_destroy();

    $json = array("isDestroyed" => $isDestroyed);
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