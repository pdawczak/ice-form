<?php

namespace Ice\FormBundle\Process\CourseApplication\Initialisation;

use Ice\FormBundle\Entity\CourseApplication;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

interface StepInitialiserInterface
{
    /**
     * Initialise a step
     *
     * @param StepInterface $step
     * @param CourseApplication $courseApplication
     * @return mixed
     */
    public function initialiseStep(StepInterface $step, CourseApplication $courseApplication);
}
