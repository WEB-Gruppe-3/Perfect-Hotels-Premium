<?php

class Hotel {

    private $id;
    private $name;
    private $imageURL;

    /* Array of this hotel's rooms */
    private $hotelRooms;

    public function __construct($id, $name, $imageURL, $hotelRooms) {
        $this->id = $id;
        $this->name = $name;
        $this->imageURL = $imageURL;
        $this->hotelRooms = $hotelRooms;
    }

}