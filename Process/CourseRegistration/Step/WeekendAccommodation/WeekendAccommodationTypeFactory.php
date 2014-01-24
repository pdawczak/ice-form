<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

use Ice\FormBundle\Process\CourseRegistration\StepFactoryInterface;
use Ice\FormBundle\Process\CourseRegistration;

class WeekendAccommodationTypeFactory implements StepFactoryInterface
{
    public function getStep(CourseRegistration $registration, $reference, $version)
    {
        return new WeekendAccommodationType($registration, $reference, $version);
    }
}

