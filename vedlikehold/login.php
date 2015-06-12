<?php
session_start();
?>

<form action="" method="post">
    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <input type="submit" />
</form>

<?php
require_once("../nettsted/php/classes/Database.php");

// Check if we have data from form
if (isset($_POST['username']) && isset($_POST['password'])) {
    // If so, check if we can find a user with this password in the database.
    $dbApi = new Database();
    $admins = $dbApi->getApprovedMaintenanceUsers();

    $isValidUserFound = false;
    foreach($admins as $user) {
        // Check if we find a corresponding entry
        if( $_POST["username"] === $user->getUsername() &&
            $_POST["password"] === $user->getPassword()) {

            // If we find a valid user, set the boolean and break this loop.
            $isValidUserFound = true;
            break;
        }
    }

    // Check if we found a valid user
    if($isValidUserFound) {
        // If so, set session as logged in, and redirect the user.
        $_SESSION['loggedin'] = 1;
        header("Location: index.php");
        exit;
    } else {
        // If not, show error message.
        echo("Feil brukernavn og/eller passord!");
    }
}
?>

