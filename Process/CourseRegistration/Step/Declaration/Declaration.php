<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\Declaration;

use Symfony\Component\Validator\Constraints as Assert;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class Declaration
{
    /**
     * @var bool
     * @Assert\True()
     */
    private $qualificationDeclaration;

    /**
     * @param $qualificationDeclaration
     * @return Declaration
     */
    public function setQualificationDeclaration($qualificationDeclaration)
    {
        $this->qualificationDeclaration = $qualificationDeclaration;
        return $this;
    }

    /**
     * @return bool
     */
    public function getQualificationDeclaration()
    {
        return $this->qualificationDeclaration;
    }

    /**
     * @param StepProgress $stepProgress
     * @return Declaration
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