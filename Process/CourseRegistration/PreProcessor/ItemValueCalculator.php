<?php

namespace Ice\FormBundle\Process\CourseRegistration\PreProcessor;

use Ice\MinervaClientBundle\Entity\Booking;
use Ice\VeritasClientBundle\Entity\Course;

class ItemValueCalculator
{
    public function recalculateValues(Booking $booking, Course $course)
    {
        foreach ($booking->getBookingItems() as $bookingItem) {
            $courseBookingItem = $course->getBookingItemByCode($bookingItem->getCode());
            if ($courseBookingItem->getPrice() === null) {
                try {
                    $relativeValue = $courseBookingItem->getAttributeByName('relativeValue')->getValue();
                    if (is_array($relativeValue)) {
                        $price = 0;
                        foreach ($relativeValue as $pattern => $ratio) {
                            foreach ($booking->getBookingItems() as $innerBookingItem) {
                                if (
                                    $course->getBookingItemByCode($innerBookingItem->getCode())->getPrice() &&
                                    preg_match($pattern, $innerBookingItem->getCode())
                                ) {
                                    $price += ($ratio * $innerBookingItem->getPrice());
                                }
                            }
                        }
                        $bookingItem->setPrice($price);
                    }
                } catch (\Exception $e) {
                    //oops
                }
            }
        }
    }
}
