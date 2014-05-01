<?php

namespace Ice\FormBundle\Process\CourseApplication\Flow;

use Ice\FormBundle\Process\CourseApplication\StepList;

class CourseApplicationFlow
{
    private $currentStepHandler;

    private $stepList;

    public function __construct($stepList)
    {
        $this->stepList = $stepList;
    }

    public function advance()
    {
        //Find the next incomplete step
        $handler = $this->currentStepHandler;
        while ($handler = $this->stepList->getHandlerAfter($handler)) {
            if (!$handler->isComplete()) {
                $this->currentStepHandler = $handler;
                return;
            }
        }

        //If all subsequent steps are complete, search again from the beginning
        $handler = null;
        while ($handler = $this->stepList->getHandlerAfter($handler)) {
            if (!$handler->isComplete()) {
                $this->currentStepHandler = $handler;
                return;
            }
        }

        //Otherwise, stick where we are.
        return;
    }

    /**
     * @param mixed $currentStepHandler
     * @return CourseApplicationFlow
     */
    public function setCurrentStepHandler($currentStepHandler)
    {
        $this->currentStepHandler = $currentStepHandler;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentStepHandler()
    {
        if (!$this->currentStepHandler) {
            $this->advance();
        }
        return $this->currentStepHandler;
    }
}
