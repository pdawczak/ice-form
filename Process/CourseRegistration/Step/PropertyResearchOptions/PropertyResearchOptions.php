<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\PropertyResearchOptions;

use Ice\MinervaClientBundle\Entity\StepProgress;

class PropertyResearchOptions
{
    /**
     * @var string
     */
    private $propertyResearchDiscount;

    /**
     * @var string
     */
    private $accommodationBefore;

    /**
     * @param string $propertyResearchDiscount
     * @return PropertyResearchOptions
     */
    public function setPropertyResearchDiscount($propertyResearchDiscount)
    {
        $this->propertyResearchDiscount = $propertyResearchDiscount;
        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyResearchDiscount()
    {
        return $this->propertyResearchDiscount;
    }

    /**
     * @param string $accommodationBefore
     * @return PropertyResearchOptions
     */
    public function setAccommodationBefore($accommodationBefore)
    {
        $this->accommodationBefore = $accommodationBefore;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccommodationBefore()
    {
        return $this->accommodationBefore;
    }

    public static function fromStepProgress(StepProgress $stepProgress)
    {
        $instance = new self();
        foreach ($stepProgress->getFieldValues() as $fieldValue) {
            $instance->{$fieldValue->getFieldName()} = $fieldValue->getValue();
        }
        return $instance;
    }
}
