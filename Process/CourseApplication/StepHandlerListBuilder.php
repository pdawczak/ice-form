<?php

namespace Ice\FormBundle\Process\CourseApplication;

use Ice\FormBundle\Entity\CourseApplication;
use Ice\FormBundle\Entity\CourseApplicationRequirement;

class StepHandlerListBuilder
{
    /**
     * @var StepDirectorInterface
     */
    private $stepDirector;

    public function __construct(
        StepDirectorInterface $stepDirector
    )
    {
        $this->stepDirector = $stepDirector;
    }

    public function buildStep($reference, $version)
    {
        return $this->stepDirector->getStepFactory($reference)->getStep($reference, $version);
    }

    /**
     * @param CourseApplicationRequirement[] $requirements
     * @return StepList
     */
    public function buildFromApplication(CourseApplication $application)
    {
        $stepList = new StepList($application);
        foreach ($application->getSteps() as $step) {
            $stepList->setHandler($this->buildStep($step->getName(), $step->getVersion()));
        }
        return $stepList;
    }
}
