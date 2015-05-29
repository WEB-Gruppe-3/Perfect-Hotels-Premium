<?php
require_once("DBConnector.php");
require_once("Conf.php");

require_once("Image.php");
require_once("RoomType.php");
require_once("Hotel.php");

/**
 * This class represents the database API.
 */
class Database {

    private $dbConnector;
    private $dbName;

    public function __construct() {
        $this->dbConnector = new DBConnector();
        $this->dbName = Conf::$DB_NAME;
    }

    /**
     * Get all the hotels present in the database.
     *
     * @return Array Returns an array of Hotel objects.
     */
    public function getHotels() { //todo remove dis: $id, $name, $image, $description, Array $roomTypes
        // TODO
    }

    /**
     * Get supported room types for a given hotel.
     *
     * @return Array Returns an array of RoomType objects.
     */
    public function getRoomTypes($hotelID) {
        // Find all room types with a certain hotel id
        $query = "SELECT * FROM HotelRoomType WHERE HotelID = '$hotelID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        $roomTypes = array();
        while($row = mysqli_fetch_assoc($result)) {
            array_push($roomTypes, $this->getRoomType($row["RoomTypeID"]));
        }

        return $roomTypes;
    }

    /**
     * Get a room type for a give ID.
     *
     * @return RoomType Returns a RoomType object.
     */
    public function getRoomType($roomTypeID) {
        $query = "SELECT * FROM RoomType WHERE ID = '$roomTypeID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        $row = mysqli_fetch_assoc($result);

        return new RoomType($row["ID"],
                            $row["Name"],
                            $row["NumOfBeds"],
                            $row["Price"],
                            $this->getImage($row["ImageID"]),
                            $row["Description"]);
    }

    /**
     * Get an Image.
     *
     * @param $imageID Integer The ID of the image.
     * @return Image Returns an Image object.
     */
    public function getImage($imageID) {
        $query = "SELECT * FROM Image WHERE ID ='$imageID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        $data = mysqli_fetch_assoc($result);
        $imageID = $data["ID"];
        $imageURL = $data["URL"];
        $imageDescription = $data["Description"];
        $image = new Image($imageID, $imageURL, $imageDescription);
        return $image;
    }

    /**
     * Insert values to a table.
     * This function takes an associative array where the KEY matches the COLUMN-name in the table.
     *
     * Example: To insert a row into table 'RoomType', you would pass an array like this.
     *
     * $arr = array( "Name" => "value",
     *               "NumOfBeds" => "value",
     *               "Price" => "value",
     *               "ImageID" => "value",
     *               "Description" => "value" );
     *
     * @param $tableName String The name of the table.
     * @param $row Array An ASSOCIATIVE array of KEY->VALUE pairs to be inserted.
     * @return boolean Returns true if successful, false otherwise.
     */
    public function insertRow($tableName, Array $row) {
        $columns = array_keys($row);
        $values = array_values($row);

        // Create two formatted strings with the columns and the values that we can insert into the query.
        $columnString = null;
        $valueString = null;

        for($i = 0; $i < count($row); $i++) {
            $columnString = $columnString . $columns[$i];
            $valueString = $valueString . "'" . $values[$i] . "'";

            // At the last loop-through, avoid adding a comma at the end.
            if(false === ($i === count($columns) - 1)) {
                $columnString = $columnString . ", ";
                $valueString = $valueString . ", ";
            }
        }

        // Now, lets make our query and fire it off!
        $query = "INSERT INTO $tableName ($columnString) VALUES($valueString);";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        return $result;
    }

    /**
     * Delete a row from a selected table.
     *
     * @param $tableName String The table from which to delete.
     * @param $id Integer The id of the row to delete.
     * @return boolean Returns true on success, false otherwise.
     */
    public function deleteRow($tableName, $id) {
        $query = "DELETE FROM $tableName WHERE ID = $id";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }

    /**
     * This function updates columns in a row.
     *
     * The $update parameter must be an associative array structured like so:
     * $update = array( "Col1ToBeUpdated" => "newValue",
     *                  "Col2ToBeUpdated" => "newValue",
     *                  "Col3ToBeUpdated" => "newValue" );
     *
     * @param $tableName String The name of the table to update.
     * @param $id Integer The id of the row to update.
     * @param $update Array An ASSOCIATIVE array with the columns and values to be updated.
     * @return boolean Returns true on success, false otherwise.
     */
    public function updateRow($tableName, $id, $update) {
        $keys = array_keys($update);
        $updateString = null;
        for($i = 0; $i < count($update); $i++) {
            $updateString = $updateString . $keys[$i] . "=" . "'" .$update[$keys[$i]] . "'";

            // Don't append comma on the last loop-through
            if(false === ($i === count($update) - 1)) {
                $updateString = $updateString . ",";
            }
        }

        $query = "UPDATE $tableName SET $updateString WHERE ID = $id";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }

    /**
     * Get a row from a table.
     *
     * @param $tableName String The name of the table from which to recieve the row.
     * @return mysqli_result Returns a result object.
     */
    public function getRow($tableName, $id) {
        $query = "SELECT * FROM $tableName WHERE ID = $id";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }

    /**
     * Get the contents of the table.
     *
     * @param $tableName String The table to get.
     * @return mysqli_result|false Returns the contents of the table as a mysqli result array.
     */
    public function getAllRows($tableName) {
        $query = "SELECT * FROM $tableName";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }

    /**
     * Checks if a row exists in the database.
     *
     * The array should look like this:
     * $row = array("Col1" => "value",
     *              "Col2" => "value");
     *
     * @param $tableName String Name of the table to check in.
     * @param $row Array An associative array with column => value pairs.
     * @return boolean Returns true if the row exists, false otherwise.
     */
    public function doesRowExist($tableName, $row) {
        $columns = array_keys($row);
        $values = array_values($row);

        // Making our WHERE string
        $conditions = null;
        for($i = 0; $i < count($row); $i++) {
            $conditions = $conditions . $columns[$i] . "=" . "'" . $values[$i] . "'";

            // Append "AND", unless its the last loop through
            if(false === ($i === count($row) - 1)) {
                $conditions = $conditions . " AND ";
            } else {
                $conditions = $conditions . ";";
            }
        }

        // Now lets make and fire off our query!
        $query = "SELECT * FROM $tableName WHERE $conditions";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        // If the result object has > 0 num_rows, the row exists
        if($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all table names in this database.
     *
     * @return mysqli_result|false Returns a mysqli result array with table names on success, false otherwise.
     */
    public function getTableNames() {
        $query = "SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$this->dbName'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return  $result;
    }

    /**
     * Get all column names for this table.
     *
     * @param $tableName String The name of the table.
     * @return mysqli_result|false Returns a mysqli result array with column names on success, false otherwise.
     */
    public function getColumnNames($tableName) {
        $query = "SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$tableName'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }
}