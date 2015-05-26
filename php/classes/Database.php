<?php
require_once("DBConnector.php");
require_once("Conf.php");

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