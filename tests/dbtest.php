<?php
require_once("../php/classes/DBConnector.php");
require_once("../php/classes/Database.php");

$dbCon = new DBConnector();
$dbApi = new Database();

/**
 * DbLink
 */
echo("<h2>Test: DbLink</h2>");
var_dump($dbCon->getDBLink());
echo("<br>");

echo("<h2>Test: Database API functions</h2>");

/**
 * getTableNames()
 */
echo("<h3>getTableNames()</h3>");

$result = $dbApi->getTableNames();
while($row = mysqli_fetch_row($result)) {
    echo($row[0] . "<br>");
}

/**
 * getColumnNames()
 */
echo("<h3>getColumnNames(\"RoomType\")</h3>");

$result = $dbApi->getColumnNames("RoomType");
while($row = mysqli_fetch_row($result)) {
    echo($row[0] . "<br>");
}

/**
 * getAllRows()
 */
echo("<h3>getAllRows(\"RoomType\")</h3>");
echo("Nothing will show here if there are no rows in the table!<br>");

$result = $dbApi->getAllRows("RoomType");

while($row = mysqli_fetch_row($result)) {
    echo($row[0] . "<br>");
}

/**
 * getImageURL()
 */
echo("<h3>getImageURL(1)</h3>");

echo($dbApi->getImageURL(1));

/**
 * insertRow()
 */
echo("<h3>insertRow()</h3>");

$tableName = "Test";
$row = array("Col1" => "ezBezt", "Col2" => "isW0rst");
$result = $dbApi->insertRow($tableName, $row);
if($result) {
    echo("Successfully inserted row in table Test");
}
else {
    echo("<strong>Inserting of row into table Test FAILED!</strong>");
}

/**
 * updateRow()
 */
echo("<h3>updateRow()</h3>");

$tableName = "Test";
$result = $dbApi->updateRow($tableName, 1, array("Col1" => "UpdatedValue"));
if($result) {
    echo("Successfully updated Col1 in table Test");
} else {
    echo("<strong>Updating of Col1 in table Test FAILED!</strong>");
}