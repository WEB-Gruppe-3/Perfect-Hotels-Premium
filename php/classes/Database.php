<?php
require_once("DBConnector.php");

/**
 * This is the database api.
 */

class Database {

    private $dbConnector;

    public function __construct() {
        $this->dbConnector = new DBConnector();
    }

    /**
     * Private functions
     */
    private function getTableAsArray($tableName) {
        $query = "SELECT * FROM $tableName";
    }


    /**
     * Metadata
     */
    public function getTableNames() {
        $query = "SELECT `table_name` FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_SCHEMA`='web-is-gr03w'";
    }

    public function getColumnNames($table) {
        $query = "SELECT `column_name` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`='$table';";
    }
}