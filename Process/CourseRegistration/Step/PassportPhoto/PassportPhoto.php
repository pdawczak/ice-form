<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\PassportPhoto;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class PassportPhoto
{
    /**
     * @var string
     */
    private $photoMethod;

    /**
     * @param string $photoMethod
     * @return PassportPhoto
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
     * @return PassportPhoto
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
