<?php
require_once("DBConnector.php");
require_once("Conf.php");

require_once("Image.php");
require_once("RoomType.php");
require_once("Room.php");
require_once("Hotel.php");
require_once("Booking.php");
require_once("MaintenanceUser.php");

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
     * Get an array of approved user that can log-in to the maintenance page.
     * @return Array Returns an array of MaintenanceUser objects.
     */
    public function getApprovedMaintenanceUsers() {
        $query = "SELECT * FROM MaintenanceUser";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        // Exit and print error if there are no users found
        if($result->num_rows < 1) {
            exit("ERROR from getApprovedMaintenanceUsers()! No MaintenanceUsers found in database.");
        }

        // Make and return an array of MaintenanceUser's
        $users = array();
        while($row = mysqli_fetch_assoc($result)) {
            $users[] = new MaintenanceUser($row["ID"], $row["UserName"], $row["Password"]);
        }

        return $users;
    }

    /**
     * Adds a new booking to a order reference
     *
     * @return boolean Returns true on success, false otherwise.
     */
    public function addBooking($orderReference, $hotelID, $roomTypeID, DateTime $startDate, DateTime $endDate) {
        // Check if the reference already exists
        if($this->getCustomerOrderID($orderReference) !== -1) {
            // If so, do nothing.
        } else {
            // If not, we need to make a new one before we proceed.
            $this->addCustomerOrder($orderReference);
        }

        // Now, lets add the booking entry.
        // To do that, we need to know its HotelRoomTypeID & CustomerOrderID.
        $customerOrderID = $this->getCustomerOrderID($orderReference);
        $hotelRoomTypeID = $this->getHotelRoomTypeID($hotelID, $roomTypeID);

        // Setting time to 00:00:00
        $startDate->setTime(0, 0, 0);
        $endDate->setTime(0, 0, 0);

        // Formatting the dates
        $sqlStartDate = $startDate->format("Y-m-d");
        $sqlEndDate = $endDate->format("Y-m-d");

        // Great, lets make our query
        $query = "INSERT INTO Booking(FromDate, ToDate, HotelRoomTypeID, CustomerOrderID) VALUES('$sqlStartDate', '$sqlEndDate', $hotelRoomTypeID, $customerOrderID)";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        return $result;
    }

    /**
     * Add a new CustomerOrder
     *
     * @return boolean Returns true on success, false otherwise.
     */
    public function addCustomerOrder($reference) {
        $query = "INSERT INTO CustomerOrder(Reference) VALUES('$reference')";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        return $result;
    }

    /**
     * Get a CustomerOrder ID from a reference
     * @param $reference String The reference of the order.
     * @return Integer Returns the ID on success, or -1 on failure.
     */
    public function getCustomerOrderID($reference) {
        $query ="SELECT ID FROM CustomerOrder WHERE Reference = '$reference'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        if($result->num_rows === 0) {
            return -1;
        } else {
            $row = mysqli_fetch_assoc($result);
            return $row["ID"];
        }
    }

    /**
     * Get a HotelRoomTypeID
     */
    public function getHotelRoomTypeID($hotelID, $roomTypeID) {
        $query = "SELECT ID FROM HotelRoomType WHERE HotelID = '$hotelID' AND RoomTypeID = '$roomTypeID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        $row = mysqli_fetch_assoc($result);
        return $row["ID"];
    }

    /**
     * Get bookings for a hotel and a room type.
     *
     * @param $hotelID Integer The hotel id.
     * @param $roomTypeID Integer The room type id.
     * @return Array Returns an array of Booking objects.
     */
    public function getBookings($hotelID, $roomTypeID) {
        // Find the HotelRoomTypeID
        $query = "SELECT ID FROM HotelRoomType WHERE HotelID = '$hotelID' AND RoomTypeID = '$roomTypeID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        $hotelRoomTypeId = intval(mysqli_fetch_assoc($result)["ID"]);

        // Get all bookings with this ID
        $query = "SELECT ID, FromDate, ToDate, RoomID FROM Booking WHERE HotelRoomTypeID = '$hotelRoomTypeId'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        // Make Booking objects
        $bookings = array();
        while($row = mysqli_fetch_assoc($result)) {
            $id = intval($row["ID"]);
            $startDate = new DateTime($row["FromDate"]);
            $startDate->setTime(0, 0, 0);
            $endDate = new DateTime($row["ToDate"]);
            $endDate->setTime(0, 0, 0);
            $roomID = $row["RoomID"];

            array_push($bookings, new Booking($id, $startDate, $endDate, $roomID));
        }
        return $bookings;
    }

    /**
     * Get all active bookings for a hotel and room type
     * @param $hotelID Integer The hotel id.
     * @param $roomTypeID Integer The room type id.
     * @return Array Returns an array of active Booking objects.
     */
    public function getActiveBookings($hotelID, $roomTypeID) {
        $bookings = $this->getBookings($hotelID, $roomTypeID);

        $activeBookings = array();
        for($i = 0; $i < count($bookings); $i++) {
            if(false === $bookings[$i]->isExpired()) {
                array_push($activeBookings, $bookings[$i]);
            }
        }

        return $activeBookings;
    }

    /**
     * Get all the hotels present in the database.
     *
     * @return Array Returns an array of Hotel objects.
     */
    public function getHotels() {
        $query = "SELECT * FROM Hotel";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        $hotels = array();
        while($row = mysqli_fetch_assoc($result)) {
            $hotel = new Hotel( $row["ID"],
                                $row["Name"],
                                $this->getImage($row["ImageID"]),
                                $row["Description"],
                                $this->getRoomTypes($row["ID"]) );
            array_push($hotels, $hotel);
        }
        return $hotels;
    }

    /**
     * Get a single Hotel
     */
    public function getHotel($hotelID) {
        $query = "SELECT * FROM Hotel WHERE ID = '$hotelID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        $row = mysqli_fetch_assoc($result);

        $hotel = new Hotel( $row["ID"],
                            $row["Name"],
                            $this->getImage($row["ImageID"]),
                            $row["Description"],
                            $this->getRoomTypes($row["ID"]) );

        return $hotel;
    }

    /**
     * Get supported room types for a given hotel.
     *
     * @param $hotelID Integer The id of the hotel.
     * @return Array Returns an array of RoomType objects.
     */
    public function getRoomTypes($hotelID) {
        // Find all room types with a certain hotel id
        $query = "SELECT * FROM HotelRoomType WHERE HotelID = '$hotelID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);

        $roomTypes = array();
        while($row = mysqli_fetch_assoc($result)) {
            array_push($roomTypes, $this->getRoomType($row["RoomTypeID"], $hotelID));
        }

        return $roomTypes;
    }

    /**
     * Get a room type object.
     *
     * @param $roomTypeID Integer The room type id.
     * @param $hotelID Integer The id of the hotel.
     * @return RoomType Returns a RoomType object.
     */
    public function getRoomType($roomTypeID, $hotelID) {
        $query = "SELECT * FROM RoomType WHERE ID = '$roomTypeID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        $row = mysqli_fetch_assoc($result);

        return new RoomType($row["ID"],
                            $row["Name"],
                            $row["NumOfBeds"],
                            $row["Price"],
                            $this->getImage($row["ImageID"]),
                            $row["Description"],
                            $this->getRooms($hotelID, $roomTypeID));
    }

    /**
     * Get rooms for a hotel and a room type.
     *
     * @param $hotelID Integer The id of the hotel.
     * @param $roomTypeID Integer The id of the room type.
     * @return Array Returns an array of Room objects.
     */
    public function getRooms($hotelID, $roomTypeID) {
        // Find the ID of HotelRoomType for a certain roomtype and hotel
        $query = "SELECT ID FROM HotelRoomType WHERE HotelID = '$hotelID' AND RoomTypeID = '$roomTypeID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $query);
        $row = mysqli_fetch_assoc($result);
        $hotelRoomTypeID = $row["ID"];

        // Get the rooms which has that hotelroomtypeid
        $roomQuery = "SELECT * FROM Room WHERE HotelRoomTypeID = '$hotelRoomTypeID'";
        $result = mysqli_query($this->dbConnector->getDBLink(), $roomQuery);

        // Make Room objects
        $rooms = array();
        while($row = mysqli_fetch_assoc($result)) {
            $id = intval($row["ID"]);
            $roomNumber = intval($row["RoomNumber"]);
            array_push($rooms, new Room($id, $roomNumber));
        }

        // Return those rooms
        return $rooms;
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
     * @param $tableName String The name of the table from which to receive the row.
     * @param $id Integer The id of the row.
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

    /**
     * Checks for the number of available rooms.
     *
     * @param $hotelID
     * @param $roomTypeID
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return int Returns the number of available rooms (can be a negative number)
     */
    public function getNumOfAvailableRooms($hotelID, $roomTypeID, DateTime $startDate, DateTime $endDate) {
        $startDate->setTime(0, 0, 0);
        $endDate->setTime(0, 0, 0);

        /** Number of available rooms */
        $searchStartTs = $startDate->getTimestamp();
        $searchEndTs = $endDate->getTimestamp();

        // Get all non-expired bookings
        $bookings = $this->getActiveBookings($hotelID, $roomTypeID);

        // Count how many bookings already exists in that timeframe.
        $numOfBusyBookings = 0;
        foreach($bookings as $book) {
            $bookStartDate = $book->getStartDate();
            $bookEndDate = $book->getEndDate();

            $bookStartTs = $bookStartDate->getTimestamp();
            $bookEndTs = $bookEndDate->getTimestamp();

            // Now, lets find out if this booking collides with the desired showSearchResults.
            // To avoid collision, the following must be true:
            // - The showSearchResults startDate and endDate must be before the booked startDate,
            //   OR the showSearchResults startDate must be after the booked endDate.

            // Check if the current booking collides with showSearchResults parameters.
            if( $searchEndTs > $bookStartTs && $searchStartTs < $bookEndTs ) {
                // If so, increment the number of busy bookings.
                $numOfBusyBookings++;
            }
        }

        // Get the total number of rooms for this hotel's roomtype.
        $rooms = $this->getRooms($hotelID, $roomTypeID);
        $numOfRooms = count($rooms);

        // Add the num of avail rooms to showSearchResults data.
        $numOfAvailableRooms = $numOfRooms - $numOfBusyBookings;

        return $numOfAvailableRooms;
    }

    /**
     * Helper function to convert string dates from JS to DateTime
     */
    function stringToDate($dateString) { // Format: 03.06.2015
        // Replace . with -
        $replace = str_replace(".", "-", $dateString);

        // Now looks like this 03-06-2015 (DD-MM-YYYY)
        // Make a DateTime
        $date = DateTime::createFromFormat("d-m-Y", $replace);

        return $date;
    }
}