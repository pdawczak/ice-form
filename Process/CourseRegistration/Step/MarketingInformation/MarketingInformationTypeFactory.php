<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation;

use Ice\FormBundle\Process\CourseRegistration\StepFactoryInterface;
use Ice\FormBundle\Process\CourseRegistration;
use Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation\BookingCodeHandler\BookingCodeHandlerManager;

class MarketingInformationTypeFactory implements StepFactoryInterface
{
    private $handlerManager;

    public function __construct(BookingCodeHandlerManager $manager)
    {
        $this->handlerManager = $manager;
    }

    public function getStep(CourseRegistration $registration, $reference, $version)
    {
        return new MarketingInformationType($registration, $reference, $this->handlerManager);
    }
}
