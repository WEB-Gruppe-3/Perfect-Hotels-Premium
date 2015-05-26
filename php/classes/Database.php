<?php
require_once("DBConnector.php");
require_once("Conf.php");
/**
 * This is the database api.
 */

class Database {

    private $dbConnector;
    private $dbName;

    public function __construct() {
        $this->dbConnector = new DBConnector();
        $this->dbName = Conf::$DB_NAME;
    }

    /**
     * Private functions
     */
    private function getTableAsArray($tableName) {
        $query = "SELECT * FROM $tableName";
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
     * @param $table String The name of the table.
     * @return mysqli_result|false Returns a mysqli result array with column names on success, false otherwise.
     */
    public function getColumnNames($table) {
        $query = "SELECT `column_name` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`='$table'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }
}