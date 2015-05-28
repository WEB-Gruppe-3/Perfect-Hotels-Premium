<?php

class HotelRoom {

    private $id;
    private $name;
    private $roomType;

    public function __construct($id, $name, $roomType) {
        $this->id = $id;
        $this->name = $name;
        $this->roomType = $roomType;
    }

}