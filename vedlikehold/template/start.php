<!-- Beginning of start.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Perfect Hotel Premium</title>
    <link rel="stylesheet" type="text/css" media="screen" href="css/vedlikehold.css" />

    <meta charset="UTF-8">
</head>

<body>

<div id="container">

    <div id="pageHeader">
        <h1>Perfect Hotel Premiums</h1>

        <!-- Logged in message -->
        <span id="loginMessage">
            Logget inn som: <?php print($_SESSION["username"]); ?>
        </span>

        <!-- Log-out button -->
        <a class="smallButton" id="logOutButton">Logg ut</a>

    </div>

    <nav>
        <ul>
          <li><a href="../nettsted/login.php">Hjem</a></li>
          <li><a href="index.php">Vedlikehold</a></li>
          <li><a href="../nettsted/checkin.php">Sjekk inn</a></li>
          <li><a href="../nettsted/editorder.php">Endre bestilling</a></li>
        </ul>
    </nav>

    <!-- End of start.html -->