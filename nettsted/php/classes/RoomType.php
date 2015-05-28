<?php

class RoomType {

    private $id;
    private $name;
    private $numOfBeds;
    private $price;
    private $imageURL;

    public function __construct($id, $name, $numOfBeds, $price, $imageURL) {
        $this->id = $id;
        $this->name = $name;
        $this->numOfBeds = $numOfBeds;
        $this->price = $price;
        $this->imageURL = $imageURL;
    }
}