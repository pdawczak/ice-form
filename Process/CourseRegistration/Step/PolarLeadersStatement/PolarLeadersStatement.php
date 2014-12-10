<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\PolarLeadersStatement;

use Ice\MinervaClientBundle\Entity\StepProgress;

class PolarLeadersStatement
{
    /**
     * @var string
     */
    private $polarExperience;

    /**
     * @var string
     */
    private $hopeToGain;

    /**
     * @return string
     */
    public function getHopeToGain()
    {
        return $this->hopeToGain;
    }

    /**
     * @param string $hopeToGain
     */
    public function setHopeToGain($hopeToGain)
    {
        $this->hopeToGain = $hopeToGain;
    }

    /**
     * @return string
     */
    public function getPolarExperience()
    {
        return $this->polarExperience;
    }

    /**
     * @param string $polarExperience
     */
    public function setPolarExperience($polarExperience)
    {
        $this->polarExperience = $polarExperience;
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
