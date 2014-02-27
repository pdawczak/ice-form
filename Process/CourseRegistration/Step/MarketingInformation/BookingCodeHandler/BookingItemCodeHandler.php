<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation\BookingCodeHandler;

use Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation\BookingCodeConstraint;
use Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation\BookingCodeHandlerInterface;
use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\VeritasClientBundle\Entity\BookingItem;
use Ice\VeritasClientBundle\Entity\Course;
use Symfony\Component\Validator\ExecutionContext;

class BookingItemCodeHandler implements BookingCodeHandlerInterface
{
    /**
     * Should validate $code and add a violation if the code is unavailable (for example, an offer has expired)
     *
     * @param string $code
     * @param BookingCodeConstraint $constraint
     * @param ExecutionContext $context
     */
    public function validate($code, BookingCodeConstraint $constraint, ExecutionContext $context)
    {
        $course = $constraint->course;
        $items = $this->getBookingItemsWithCode($code, $course);
        $validItems = $this->filterByMaxDate($items);

        if (!$validItems) {
            $context->addViolation("This booking code is no longer valid");
        }

        $validItems = $this->filterByMinDate($validItems);
        if (!$validItems) {
            $context->addViolation("This booking code is not valid yet");
        }
    }

    /**
     * Add or remove booking items as appropriate.
     *
     * Return true if the booking has changed (needs to be persisted), false otherwise
     *
     * @param $code
     * @param \Ice\MinervaClientBundle\Entity\Booking $booking
     * @param \Ice\VeritasClientBundle\Entity\Course $course
     * @param \Ice\JanusClientBundle\Entity\User $user
     * @return bool
     */
    public function updateBooking($code, Booking $booking, Course $course, User $user)
    {
        $items = $this->getBookingItemsWithCode($code, $course);
        $validItems = $this->filterByMaxDate($items);
        $validItems = $this->filterByMinDate($validItems);

        $keyedValidItems = [];
        foreach ($validItems as $item) {
            $keyedValidItems[$item->getCode()] = $item;
        }

        //Make sure all of the valid items are present
        $currentItems = $booking->getBookingItems();

        foreach ($currentItems as $currentItem) {
            if (isset($keyedValidItems[$currentItem->getCode()])) {
                //Item is already here. Forget about this one and move on
                unset($keyedValidItems[$currentItem->getCode()]);
            }
        }


        //Any valid items we have left need to be added to the booking.
        $persistRequired = (count($keyedValidItems) > 0);
        foreach ($keyedValidItems as $validItem) {
            $booking->addBookingItemByCourseBookingItem($validItem);
        }

        return $persistRequired;
    }

    private function getBookingItemsWithCode($value, Course $course)
    {
        $items = [];

        foreach ($course->getBookingItems() as $bookingItem) {
            if ($attribute = $bookingItem->getAttributeByName('applyWithCodePattern')) {

                $patterns = $attribute->getValue();

                if (!is_array($patterns)) {
                    $patterns = [$patterns];
                }

                foreach ($patterns as $pattern) {
                    if (preg_match('#^'.$pattern.'$#i', $value)) {
                        $items[] = $bookingItem;
                        break;
                    }
                }
            }
        }

        return $items;
    }

    /**
     * @param BookingItem[] $items
     * @return BookingItem[]
     */
    private function filterByMaxDate(array $items)
    {
        $unexpired = [];
        foreach ($items as $item) {
            if ($maxDate = $item->getAttributeByName('codeValidBefore')) {
                $maxDate = $maxDate->getValue();
                if ((new \DateTime()) < $maxDate) {
                    $unexpired[] = $item;
                }
            }
        }
        return $unexpired;
    }

    /**
     * @param BookingItem[] $items
     * @return BookingItem[]
     */
    private function filterByMinDate(array $items)
    {
        $available = [];
        foreach ($items as $item) {
            if ($minDate = $item->getAttributeByName('codeValidAfter')) {
                $minDate = $minDate->getValue();
                if ((new \DateTime()) > $minDate) {
                    $available[] = $item;
                }
            }
        }
        return $available;
    }

    /**
     * Return true if this handler recognises the given code in the context of the course and user
     *
     * @param string $code
     * @param \Ice\VeritasClientBundle\Entity\Course $course
     * @param \Ice\JanusClientBundle\Entity\User $user
     * @return bool
     */
    public function canHandle($code, Course $course, User $user)
    {
        return count($this->getBookingItemsWithCode($code, $course))>0;
    }
}
