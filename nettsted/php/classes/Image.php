<?php

class Image {

    private $id;
    private $url;
    private $description;

    function __construct($id, $url, $description) {
        $this->id = $id;
        $this->url = $url;
        $this->description = $description;
    }

    /**
     * @return Integer Returns the ID of this image.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return String Returns the URL of this image.
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @return String Returns the description of this image.
     */
    public function getDescription() {
        return $this->description;
    }

}