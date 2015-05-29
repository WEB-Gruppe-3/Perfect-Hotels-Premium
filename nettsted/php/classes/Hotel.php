<?php

class Hotel {

    private $id;
    private $name;
    private $image;
    private $description;

    // Array of supported room types
    private $roomTypes;

    public function __construct($id, $name, Image $image, $description, Array $roomTypes) {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->description = $description;
        $this->roomTypes = $roomTypes;
    }

    /**
     * @return Integer The ID of this hotel.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return String The name of this hotel.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return String The image URL of this hotel.
     */
    public function getImageURL() {
        return $this->image->getUrl();
    }

    /**
     * @return String The description of this hotel.
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Get the types of rooms this hotel will offer.
     * @return Array An array of RoomType objects.
     */
    public function getRoomTypes() {
        return $this->roomTypes;
    }

}