<?php

namespace Ice\FormBundle\Process\CourseApplication;

use Ice\FormBundle\Entity\CourseApplication;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class StepList
{
    /**
     * @var StepInterface[]
     */
    private $stepHandlers;

    private $courseApplication;

    public function __construct (
        CourseApplication $courseApplication
    ) {
        $this->courseApplication = $courseApplication;
    }

    public function getHandler($reference)
    {
        return $this->stepHandlers[$reference];
    }

    public function getHandlers()
    {
        return $this->stepHandlers;
    }

    public function setHandler(StepInterface $handler)
    {
        $this->stepHandlers[$handler->getReference()] = $handler;
    }

    public function getState($reference)
    {
        return $this->courseApplication->getStep($reference);
    }

    /**
     * @param string $reference
     * @return StepInterface
     */
    public function getStepByReference($reference)
    {
        foreach ($this->stepHandlers as $step) {
            if ($step->getReference() === $reference) {
                return $step;
            }
        }
        return null;
    }

    public function getHandlerAfter($stepHandler)
    {
        if (null === $stepHandler) {
            return reset($this->stepHandlers);
        }

        $reverseList = array_reverse($this->stepHandlers);

        foreach ($reverseList as $current) {
            if ($current === $stepHandler) {
                if (isset($nextStep)) {
                    return $nextStep;
                } else {
                    return null;
                }
            }
            $nextStep = $current;
        }
    }
}

