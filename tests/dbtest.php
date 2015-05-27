<?php
require_once("../php/classes/DBConnector.php");
require_once("../php/classes/Database.php");

$dbCon = new DBConnector();
$dbApi = new Database();

/**
 * DbLink
 */
echo("<h2>Test: DbLink</h2>");
$dbLink = $dbCon->getDBLink();
if($dbLink != null && $dbLink->connect_error === null && $dbLink->errno === 0) {
    echo("<span style='color:limegreen'>Database connection is GOOD!</span>");
}
else {
    echo("<span style='color:red'>Error with database connection :( !</span>");
}

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
    echo("<span style='color:limegreen'>Successfully inserted row in table Test!</span>");
}
else {
    echo("<span style='color:red'><strong>Inserting of row into table Test FAILED!</strong></span>");
}

/**
 * updateRow()
 */
echo("<h3>updateRow()</h3>");

$tableName = "Test";
$update = array("Col1" => "UpdatedValue");
$result = $dbApi->updateRow($tableName, 1, $update);
if($result) {
    echo("<span style='color:limegreen'>Updating of " . array_keys($update)[0] . " in table " . $tableName . " is SUCCESS!</span>");
} else {
    echo("<span style='color:red'><strong>Updating of " . array_keys($update)[0] . " in table " . $tableName . " FAILED!!!!</strong></span>");
}