<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\PropertyResearchExperience;

use Ice\MinervaClientBundle\Entity\StepProgress;

class PropertyResearchExperience
{
    /**
     * @var string
     */
    private $cvMethod;

    /**
     * @var string
     */
    private $propertyResearchExperience;

    /**
     * @var string
     */
    private $confirmPropertyResearchExperience;

    /**
     * @param string $propertyResearchDiscount
     * @return PropertyResearchExperience
     */
    public function setCvMethod($propertyResearchDiscount)
    {
        $this->cvMethod = $propertyResearchDiscount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCvMethod()
    {
        return $this->cvMethod;
    }

    /**
     * @param string $accommodationBefore
     * @return PropertyResearchExperience
     */
    public function setPropertyResearchExperience($accommodationBefore)
    {
        $this->propertyResearchExperience = $accommodationBefore;
        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyResearchExperience()
    {
        return $this->propertyResearchExperience;
    }

    /**
     * @param string $confirmPropertyResearchExperience
     * @return PropertyResearchExperience
     */
    public function setConfirmPropertyResearchExperience($confirmPropertyResearchExperience)
    {
        $this->confirmPropertyResearchExperience = $confirmPropertyResearchExperience;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmPropertyResearchExperience()
    {
        return $this->confirmPropertyResearchExperience;
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
