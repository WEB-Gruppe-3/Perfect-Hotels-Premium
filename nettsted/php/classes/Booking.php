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

        // Setting time to 00:00:00
        $this->startDate->setTime(0, 0 ,0);
        $this->endDate->setTime(0, 0, 0);
    }

    /**
     * Checks if this booking has expired.
     * A booking has expired if it's end date is today, or earlier.
     */
    public function isExpired() {
        $today = new DateTime();
        $today->setTime(0, 0, 0);

        $todayTS = $today->getTimestamp();
        $endTS = $this->getEndDate()->getTimestamp();

        if($endTS <= $todayTS) {
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