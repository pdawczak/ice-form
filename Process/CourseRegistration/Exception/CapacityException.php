<?php
namespace Ice\FormBundle\Process\CourseRegistration\Exception;

use Ice\VeritasClientBundle\Entity\BookingItem;

class CapacityException extends \RuntimeException
{
    /**
     * @var  BookingItem
     */
    private $bookingItem;

    /*
     * @param \Ice\VeritasClientBundle\Entity\BookingItem $bookingItem
     * @return CapacityException
     */
    public function setBookingItem(BookingItem $bookingItem)
    {
        $this->bookingItem = $bookingItem;
        return $this;
    }

    /**
     * Get the veritas client booking item which was responsible for this exception being thrown
     *
     * @return \Ice\VeritasClientBundle\Entity\BookingItem
     */
    public function getBookingItem()
    {
        return $this->bookingItem;
    }
}