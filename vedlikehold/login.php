<?php
session_start();

// laster kun inn koden under hvis GET variabelen "login" er satt.
if (isset($_GET['login'])) {

    // laster inn koden under hvis brukernavn og passord er riktig
     if ($_POST['username'] === 'admin' &&
         $_POST['password'] === 'admin') {

         // setter session variablen
         $_SESSION['loggedin'] = 1;

         // Her viser den videre til en den beskyttede siden
         header("Location: index.php");
         exit;

     } else {
         // Hvis ikke den blir vist videre, så kommer denne erroren
         echo "Brukernavn eller passord stemmer ikke";
     }
}
 
?>
Log in:
<form action="?login=1" method="post">
Username: <input type="text" name="username" />
Password: <input type="password" name="password" />
<input type="submit" />
</form>