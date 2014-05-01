<?php

namespace Ice\FormBundle\Process\CourseApplication\Persistence;

use Ice\FormBundle\Entity\CourseApplicationFieldValue;
use Ice\FormBundle\Entity\CourseApplicationStep;

class DataToStateConverter implements DataToStateConverterInterface
{
    /**
     * Populates step state based on a data object
     *
     * @param mixed $data
     * @param CourseApplicationStep $stepState
     */
    public function setStateFromData($data, CourseApplicationStep $stepState)
    {
        foreach (get_class_methods($data) as $getter)
        {
            if (substr($getter, 0, 3) === 'get') {
                $propertyName = lcfirst(substr($getter, 3));
                $stepState->setValue(
                    (new CourseApplicationFieldValue($propertyName, 1, 'test', $data->$getter()))
                );
            }
        }
    }
}
