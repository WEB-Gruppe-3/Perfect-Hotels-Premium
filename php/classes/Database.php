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
     * TODO: remove this. $id, $name, $imageURL, $hotelRooms
     * Creates and returns all Hotel objects.
     * @return array Returns an array of Hotel objects.
     */
    public function getHotels() {
        // Getting all rows from relevant tables so we can create hotel objects
        $result_image = $this->getAllTableRows("Bilder");
        $result_hotel = $this->getAllTableRows("Hotell");
        $result_hotelRoom = $this->getAllTableRows("HR");
        $result_room = $this->getAllTableRows("Rom");
        $result_roomType = $this->getAllTableRows("RomType");

        // Creating an array of RoomTypes
        $roomTypes = array();

        while($row = mysqli_fetch_assoc($result_roomType)) {
            $id = $row["idRomType"];
            $name = $row["Navn"];
            $beds = $row["Senger"];
            $price = $row["Pris"];
            $imageURL = $this->getImageURL($row["Bilder_idBilder"]);

            array_push($roomTypes, new RoomType($id, $name, $beds, $price, $imageURL));
        }

        // Creating an array of HotelRooms
        //TODO: $hotelRooms = array();

    }

    /**
     * Get the image URL from image ID
     */
    public function getImageURL($id) {
        $query = "SELECT Bildeurl FROM Bilder WHERE idBilder = $id";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return mysqli_fetch_row($result)[0];
    }

    /**
     * Get the contents of the table.
     * @param $tableName String The table to get.
     * @return mysqli_result|false Returns the contents of the table as a mysqli result array.
     */
    public function getAllTableRows($tableName) {
        $query = "SELECT * FROM $tableName";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }

    /**
     * Get all table names in this database.
     * @return mysqli_result|false Returns a mysqli result array with table names on success, false otherwise.
     */
    public function getTableNames() {
        $query = "SELECT `table_name` FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_SCHEMA`='$this->dbName'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return  $result;
    }

    /**
     * Get all column names for this table.
     * @param $tableName String The name of the table.
     * @return mysqli_result|false Returns a mysqli result array with column names on success, false otherwise.
     */
    public function getColumnNames($tableName) {
        $query = "SELECT `column_name` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`='$tableName'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }
}