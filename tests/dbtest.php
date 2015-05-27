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
echo("<h3>getImageURL(1)</h3>");
echo($dbApi->getImageURL(1));

/* Function: insertRow() */
echo("<h3>insertRow()</h3>");
$tableName = "Test";
$row = array("Col1" => "Val1", "Col2" => "Val2");
$result = $dbApi->insertRow($tableName, $row);
echo("Success: " . $result);

/* Function: updateRow() */
echo("<h3>insertRow()</h3>");

//$dbApi->updateRow("RoomType", 1, array("Name" => "newName", "NumOfBeds" => "7"));

/* Function: getHotels() */
//echo("<h3>getHotels()</h3>");
//echo($dbApi->getHotels());