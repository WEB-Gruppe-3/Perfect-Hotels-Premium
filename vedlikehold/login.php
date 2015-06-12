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

    foreach($admins as $user) {
        // Check if we find a corresponding entry
        if( $_POST["username"] === $user->getUsername() &&
            $_POST["password"] === $user->getPassword()) {
            // If we find a valid user, set the username in session and break out of this loop.
            $_SESSION["username"] = $user->getUsername();
            break;
        }
    }

    // Check if we found a valid user
    if(isset($_SESSION["username"])) {
        // If so, set session as logged in, and redirect the user.
        $_SESSION['loggedin'] = 1;
        header("Location: index.php");
        exit;
    } else {
        // If not, show error message.
        echo("Feil brukernavn og/eller passord!");
    }
} else {
    // If there is no form data, we should send the user to the maintenance page if he is already logged in
    if(isset($_SESSION["loggedin"])) {
        if($_SESSION["loggedin"] === 1) {
            header("Location: index.php");
            exit;
        }
    }
}
?>

