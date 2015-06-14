<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login vedlikehold</title>
        <meta charset="UTF8">
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <!-- Google font: Open Sans -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    </head>

    <body>

        <div id="container">
            <h1>Login vedlikehold</h1>

            <form action="" method="post" id="loginForm">
                <table>
                    <tr><td><input id="usernameInput" type="text" name="username" required></td></tr>
                    <tr><td><input id="passwordInput" type="password" name="password" required></td></tr>
                    <tr><td><button type="submit">Log in</button></td></tr>
                </table>
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
        echo("<div id='errorMessage'>Feil brukernavn og/eller passord!</div>");
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

        </div>

        <script src="../nettsted/js/jquery-1.11.3.js"></script>
        <script src="js/login.js"></script>
    </body>
</html>