<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\VeritasClientBundle\Entity\Course;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ExecutionContext;

interface BookingCodeHandlerInterface
{
    /**
     * Should validate $code and add a violation if the code is unavailable (for example, an offer has expired)
     *
     * @param string $code
     * @param BookingCodeConstraint $constraint
     * @param ExecutionContext $context
     */
    public function validate($code, BookingCodeConstraint $constraint, ExecutionContext $context);

    /**
     * Return true if this handler recognises the given code in the context of the course and user
     *
     * @param string $code
     * @param \Ice\VeritasClientBundle\Entity\Course $course
     * @param \Ice\JanusClientBundle\Entity\User $user
     * @return bool
     */
    public function canHandle($code, Course $course, User $user);

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
    public function updateBooking($code, Booking $booking, Course $course, User $user);
}
