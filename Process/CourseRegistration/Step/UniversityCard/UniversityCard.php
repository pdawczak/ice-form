<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\UniversityCard;

use Ice\MinervaClientBundle\Exception\NotFoundException;
use Ice\MinervaClientBundle\Entity\StepProgress;

class UniversityCard
{
    /**
     * @var string
     */
    private $photoMethod;

    /**
     * @param string $photoMethod
     * @return UniversityCard
     */
    public function setPhotoMethod($photoMethod)
    {
        $this->photoMethod = $photoMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoMethod()
    {
        return $this->photoMethod;
    }

    /**
     * @param StepProgress $stepProgress
     * @return UniversityCard
     */
    public static function fromStepProgress(StepProgress $stepProgress)
    {
        $instance = new self();

        foreach (get_object_vars($instance) as $fieldName => $currentValue) {
            $setter = 'set' . ucfirst($fieldName);
            try {
                $instance->$setter(
                    $stepProgress->getFieldValueByName($fieldName)->getValue()
                );
            } catch (NotFoundException $e) {
                //Value not present in the step progress - so leave it at its default
            }
        }

        return $instance;
    }
}