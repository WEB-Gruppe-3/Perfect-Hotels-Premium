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

/* Function: getColumnNames() */
echo("<h3>getColumnNames(\"RoomType\")</h3>");
$result = $dbApi->getColumnNames("RoomType");
while($row = mysqli_fetch_row($result)) {
    echo($row[0] . "<br>");
}

/* Function: getAllRows() */
echo("<h3>getAllRows(\"RoomType\")</h3>");
echo("Nothing will show here if there are no rows in the table!<br>");
$result = $dbApi->getAllRows("RoomType");

while($row = mysqli_fetch_row($result)) {
    echo($row[0] . "<br>");
}

/* Function: getImageURL(1) */
echo("<h3>getAllRows(1)</h3>");
echo($dbApi->getImageURL(1));

/* Function: insertRow() */
echo("<h3>insertRow()</h3>");
echo($dbApi->insertRow("RoomType", $arr = array("Key1" => "Val1", "Key2" => "Val2", "Key3" => "Val3")));


/* Function: getHotels() */
//echo("<h3>getHotels()</h3>");
//echo($dbApi->getHotels());