<?php

namespace Ice\FormBundle\Infrastructure\Minerva;

use Ice\FormBundle\Entity\BookingItem;
use Ice\MinervaClientBundle\Entity\Booking as MinervaClientBooking;
use Ice\FormBundle\Entity\Booking as NativeBooking;

class MinervaClientBookingAdapter
{
    /**
     * @param MinervaClientBooking $mcBooking
     * @return NativeBooking
     */
    public function getBooking(MinervaClientBooking $mcBooking)
    {
        $nativeBookingItems = [];

        foreach ($mcBooking->getBookingItems() as $mcBookingItem) {
            $nativeBookingItems[] = (new BookingItem())
                ->setCode($mcBookingItem->getCode())
                ->setPriceInPence($mcBookingItem->getPrice())
            ;
        }

        return (new NativeBooking())
            ->setId($mcBooking->getId())
            ->setBookingItems($nativeBookingItems)
        ;
    }
}
