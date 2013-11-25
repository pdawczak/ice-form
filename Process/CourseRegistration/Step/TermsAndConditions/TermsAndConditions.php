<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\TermsAndConditions;

use Symfony\Component\Validator\Constraints as Assert;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class TermsAndConditions
{
    /**
     * @var bool
     * @Assert\True(message="You must confirm to continue")
     */
    private $termsAndConditions;

    /**
     * @param boolean $termsAndConditions
     */
    public function setTermsAndConditions($termsAndConditions)
    {
        $this->termsAndConditions = $termsAndConditions;
    }

    /**
     * @return boolean
     */
    public function getTermsAndConditions()
    {
        return $this->termsAndConditions;
    }

    /**
     * @param StepProgress $stepProgress
     * @return TermsAndConditions
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