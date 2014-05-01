<?php

namespace Ice\FormBundle\Process\CourseApplication\Persistence;

use Ice\FormBundle\Entity\CourseApplicationStep;

class StateToDataConverter implements StateToDataConverterInterface
{
    /**
     * Populates a step's data object
     *
     * @param CourseApplicationStep $stepState
     * @param mixed $data
     */
    public function setDataFromState(CourseApplicationStep $stepState, $data)
    {
        foreach ($stepState->getValues() as $stateValue) {
            $fieldName = $stateValue->getName();
            $rawValue = $stateValue->getValue();

            if (method_exists($data, $setter = 'set'.ucfirst($fieldName))) {
                $data->$setter($rawValue);
            }
        }
    }
}
