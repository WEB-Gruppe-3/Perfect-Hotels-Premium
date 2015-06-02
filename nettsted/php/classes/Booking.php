<?php

class Booking {

    private $id;
    private $startDate;
    private $endDate;
    private $roomID;

    public function __construct($id, DateTime $startDate, DateTime $endDate, $roomID) {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->roomID = $roomID;
    }

    /**
     * @return bool Returns True if the booking is expired, false otherwise.
     */
    public function isExpired() {
        $endDateTimeStamp = $this->endDate->getTimestamp();
        $now = new DateTime();
        $nowTimeStamp = $now->getTimestamp();

        if($endDateTimeStamp < $nowTimeStamp) {
            return true;
        } else {
            return false;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getRoomID() {
        return $this->roomID;
    }

}