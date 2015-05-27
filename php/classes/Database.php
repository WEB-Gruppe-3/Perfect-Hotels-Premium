<?php
require_once("DBConnector.php");
require_once("Conf.php");
require_once("Hotel.php");
require_once("RoomType.php");

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
     * Creates and returns all Hotel objects.
     *
     * @return array Returns an array of Hotel objects.
     */
    public function getHotels() { // TODO!
        // Getting all rows from relevant tables so we can create hotel objects
        /*
        $result_image = $this->getAllRows("Image");
        $result_hotel = $this->getAllRows("Hotel");
        $result_hotelRoom = $this->getAllRows("HotelRoomType");
        $result_room = $this->getAllRows("Room");
        $result_roomType = $this->getAllRows("RoomType");

        // Creating an array of RoomTypes
        $roomTypes = array();

        while($row = mysqli_fetch_assoc($result_roomType)) {
            $id = $row["ID"];
            $name = $row["Name"];
            $beds = $row["NumOfBeds"];
            $price = $row["Price"];
            $imageURL = $this->getImageURL($row["ImageID"]);

            array_push($roomTypes, new RoomType($id, $name, $beds, $price, $imageURL));
        }

        // Creating an array of HotelRooms //TODO: $id, $name, $roomType
        */
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
            $valueString = $valueString . $values[$i];

            // At the last loop-through, avoid adding a comma at the end.
            if(false === ($i === count($columns) - 1)) {
                $columnString = $columnString . ", ";
                $valueString = $valueString . ", ";
            }
        }

        // Now, lets make our query and fire it off!
        $query = "INSERT INTO $tableName ($columnString) VALUES($valueString)";
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
     * Get the image URL from image ID.
     *
     * @param $id Integer The ID of the image.
     * @return String The image URL.
     */
    public function getImageURL($id) {
        $query = "SELECT URL FROM Image WHERE ID = $id";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return mysqli_fetch_row($result)[0];
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
     * Get all table names in this database.
     *
     * @return mysqli_result|false Returns a mysqli result array with table names on success, false otherwise.
     */
    public function getTableNames() {
        $query = "SELECT `table_name` FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_SCHEMA`='$this->dbName'";
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
        $query = "SELECT `column_name` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`='$tableName'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }
}