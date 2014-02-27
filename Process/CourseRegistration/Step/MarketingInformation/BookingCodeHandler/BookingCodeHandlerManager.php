<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation\BookingCodeHandler;

use Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation\BookingCodeHandlerInterface;

class BookingCodeHandlerManager
{
    private $bookingCodeHandlers = [];

    public function addBookingCodeHandler($handler, $priority = 0)
    {
        if (!isset($this->bookingCodeHandlers[$priority])) {
            $this->bookingCodeHandlers[$priority] = [];
        }
        $this->bookingCodeHandlers[$priority][] = $handler;
        ksort($this->bookingCodeHandlers);
        return $this;
    }

    public function getHandlerFor($value, $course, $user)
    {
        foreach ($this->bookingCodeHandlers as $handlers) {
            foreach($handlers as $handler)
            {
                /** @var BookingCodeHandlerInterface $handler */
                if ($handler->canHandle($value, $course, $user)) {
                    return $handler;
                }
            }
        }
    }
}