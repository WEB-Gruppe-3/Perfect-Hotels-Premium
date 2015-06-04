<?php

class Room {

    private $id;
    private $roomNumber;

    public function __construct($id, $roomNumber) {
        $this->id = $id;
        $this->roomNumber = $roomNumber;
    }

    public function getId() {
        return $this->id;
    }

    public function getRoomNumber() {
        return $this->roomNumber;
    }

}