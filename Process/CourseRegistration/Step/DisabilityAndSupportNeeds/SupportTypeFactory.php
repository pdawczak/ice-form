<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\DisabilityAndSupportNeeds;

use Ice\FormBundle\Process\CourseRegistration\StepFactoryInterface;
use Ice\FormBundle\Process\CourseRegistration;

class SupportTypeFactory implements StepFactoryInterface
{
    public function getStep(CourseRegistration $registration, $reference, $version)
    {
        /**
         * This factory is used for references disabilityAndSupportNeeds AND nonMadingleyDisaibilityAndSupportNeeds,
         * but the DisabilityAndSupportNeedsType is able to handle both by inspecting the passed $reference
         */
        return new DisabilityAndSupportNeedsType($registration, $reference, $version);
    }
}
