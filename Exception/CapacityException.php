<?php
namespace Ice\FormBundle\Exception;

use Ice\VeritasClientBundle\Entity\BookingItem as CourseBookingItem;
use Ice\MinervaClientBundle\Entity\BookingItem;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\VeritasClientBundle\Entity\Course;

class CapacityException extends \Exception
{
    /** @var  Booking */
    private $booking;

    /** @var  Course */
    private $course;

    /**
     * @param \Ice\MinervaClientBundle\Entity\Booking $booking
     * @return CapacityException
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param \Ice\VeritasClientBundle\Entity\Course $course
     * @return CapacityException
     */
    public function setCourse($course)
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return \Ice\VeritasClientBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }
}