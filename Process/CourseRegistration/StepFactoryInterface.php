<?php

namespace Ice\FormBundle\Process\CourseRegistration;

use Ice\FormBundle\Process\CourseRegistration;

interface StepFactoryInterface
{
    public function getStep(CourseRegistration $registration, $reference, $version);
}
