<?php

class RoomType {

    private $id;
    private $name;
    private $numOfBeds;
    private $price;
    private $image;
    private $description;

    function __construct($id, $name, $numOfBeds, $price, Image $image, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->numOfBeds = $numOfBeds;
        $this->price = $price;
        $this->image = $image;
        $this->description = $description;
    }

    /**
     * @return Integer Returns the room type id.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return String Returns the room type name.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return Integer Returns this' room types number of beds.
     */
    public function getNumOfBeds() {
        return $this->numOfBeds;
    }

    /**
     * @return Integer Returns the price of rent for this room type.
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @return String Returns the image URL for this room type.
     */
    public function getImageURL() {
        return $this->image->getUrl();
    }

    /**
     * @return String Returns the description for this room type.
     */
    public function getDescription() {
        return $this->description;
    }

}