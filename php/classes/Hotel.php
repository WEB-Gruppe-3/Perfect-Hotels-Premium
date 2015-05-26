<?php

class Hotel {

    private $id;
    private $name;
    private $imageURL;

    /* Array of this hotel's rooms */
    private $rooms;

    public function __construct($id, $name, $imageURL, $rooms) {
        $this->id = $id;
        $this->name = $name;
        $this->imageURL = $imageURL;
        $this->rooms = $rooms;
    }

}