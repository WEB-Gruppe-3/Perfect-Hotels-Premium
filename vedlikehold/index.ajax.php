<?php


if(isset($_GET["request"])) {

    switch($_GET["request"]) {
        case "logOut":
            logOut($_GET["sessionID"]);
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