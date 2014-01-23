<?php

namespace Ice\FormBundle\Process\CourseRegistration;

use Ice\FormBundle\Process\CourseRegistration;

class DefaultStepFactory implements StepFactoryInterface
{
    /**
     * @param \Ice\FormBundle\Process\CourseRegistration $registration
     * @param $reference
     * @param $version
     * @return mixed
     */
    public function getStep(CourseRegistration $registration, $reference, $version)
    {
        $className = 'Ice\\FormBundle\\Process\\CourseRegistration\\Step\\' . ucwords($reference) . '\\' . ucwords($reference) . 'Type';
        return new $className($registration, $reference);
    }
}
