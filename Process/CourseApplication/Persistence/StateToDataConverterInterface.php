<?php

namespace Ice\FormBundle\Process\CourseApplication\Persistence;

use Ice\FormBundle\Entity\CourseApplicationStep;

interface StateToDataConverterInterface
{
    /**
     * Populates a step's data object
     *
     * @param CourseApplicationStep $stepState
     * @param mixed $data
     */
    public function setDataFromState(CourseApplicationStep $stepState, $data);
}
