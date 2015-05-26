<?php
require_once("Conf.php");

class DBConnector {

    private $dbLink = null;

    public function __construct() {
        $this->dbLink = $this->getNewDBLink();
    }

    /**
     * Returns this' class private $dbLink.
     */
    public function getDBLink() {
        if($this->isLinkValid($this->dbLink)) {
            return $this->dbLink;
        }
        else {
            $this->dbLink = $this->getNewDBLink();
            return $this->dbLink;
        }
    }

    /**
     * Returns a new database connection/link.
     */
    private function getNewDBLink() {
        return mysqli_connect(Conf::$DB_HOSTNAME, Conf::$DB_USERNAME, Conf::$DB_PASSWORD, Conf::$DB_NAME);
    }

    /**
     * Returns true if the $dbLink is a working mysqli link, false otherwise.
     */
    private function isLinkValid($dbLink) {
        if(isset($dbLink) && $dbLink instanceof mysqli && $dbLink->connect_errno === 0) {
            return true;
        }

        return false;
    }

}
