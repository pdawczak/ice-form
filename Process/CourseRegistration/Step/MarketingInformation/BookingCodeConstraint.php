<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation;

use Symfony\Component\Validator\Constraint;
use Ice\VeritasClientBundle\Entity\Course;
use Ice\JanusClientBundle\Entity\User;

/**
 * @Annotation
 */
class BookingCodeConstraint extends Constraint
{
    /** @var Course */
    public $course;

    /** @var User */
    public $user;

    public function validatedBy()
    {
        return 'booking_code_validator';
    }

    public function getRequiredOptions()
    {
        return ['course', 'user'];
    }
}
