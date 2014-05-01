<?php

namespace Ice\FormBundle\Process\CourseApplication\Persistence;

use Ice\FormBundle\Entity\CourseApplicationStep;

interface DataToStateConverterInterface
{
    /**
     * Populates step state based on a data object
     *
     * @param mixed $data
     * @param CourseApplicationStep $stepState
     */
    public function setStateFromData($data, CourseApplicationStep $stepState);
}
