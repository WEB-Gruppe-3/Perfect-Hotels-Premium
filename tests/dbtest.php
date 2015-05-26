<?php
require_once("../php/classes/DBConnector.php");
require_once("../php/classes/Database.php");

$dbCon = new DBConnector();
$dbApi = new Database();

// Testing DBConnector dbLink
echo("<h2>Test: DbLink</h2>");
var_dump($dbCon->getDBLink());
echo("<br>");

// Testing DatabaseAPI functions
echo("<h2>Test: Database API functions</h2>");

/* Function: getTableNames() */
echo("<h3>getTableNames()</h3>");
$result = $dbApi->getTableNames();
while($row = mysqli_fetch_row($result)) {
    echo($row[0] . "<br>");
}

/* Function: getColumnNames("RomType") */
echo("<h3>getColumnNames(\"RomType\")</h3>");
$result = $dbApi->getColumnNames("RomType");
while($row = mysqli_fetch_row($result)) {
    echo($row[0] . "<br>");
}

/* Function: getAllTableRows("RomType") */
echo("<h3>getAllTableRows(\"RomType\")</h3>");
echo("Nothing will show here if there are no rows in the table!<br>");
$result = $dbApi->getAllTableRows("RomType");

while($row = mysqli_fetch_row($result)) {
    echo($row[0] . "<br>");
}