<?php

namespace Ice\FormBundle\Entity;

class Booking
{
    /**
     * @var BookingItem[]
     */
    private $bookingItems;

    /**
     * @var int
     */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param array|BookingItem[] $bookingItems
     * @return $this
     */
    public function setBookingItems(array $bookingItems)
    {
        $this->bookingItems = $bookingItems;
        return $this;
    }

    /**
     * @return BookingItem[]
     */
    public function getBookingItems()
    {
        return $this->bookingItems;
    }
}
