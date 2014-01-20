<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\ElmSeminarChoices;

use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ElmSeminarChoices
 * @package Ice\FormBundle\Process\CourseRegistration\Step\ElmSeminarChoices
 * @Assert\Callback(methods={
 *  {"Ice\FormBundle\Process\CourseRegistration\Step\ElmSeminarChoices\ElmSeminarChoicesValidator", "areChoicesValid"},
 *  {"Ice\FormBundle\Process\CourseRegistration\Step\ElmSeminarChoices\ElmSeminarChoicesValidator", "isSeminarChoicesPersonalStatementValid"}
 * })
 */
class ElmSeminarChoices
{
    /**
     * @var string
     */
    private $seminarChoicesFirstChoice;

    /**
     * @var string
     */
    private $seminarChoicesSecondChoice;

    /**
     * @var string
     */
    private $seminarChoicesThirdChoice;

    /**
     * @var string
     */
    private $seminarChoicesPersonalStatement;

    /**
     * @param StepProgress $stepProgress
     * @return ElmSeminarChoices
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setSeminarChoicesPersonalStatement($instance->getDeserializedValueByFieldName($stepProgress, 'seminarChoicesPersonalStatement'));
        $instance->setSeminarChoicesFirstChoice($instance->getDeserializedValueByFieldName($stepProgress, 'seminarChoicesFirstChoice'));
        $instance->setSeminarChoicesSecondChoice($instance->getDeserializedValueByFieldName($stepProgress, 'seminarChoicesSecondChoice'));
        $instance->setSeminarChoicesThirdChoice($instance->getDeserializedValueByFieldName($stepProgress, 'seminarChoicesThirdChoice'));
        return $instance;
    }

    /**
     * @param StepProgress $stepProgress
     * @param $fieldName
     * @return mixed|null
     */
    private function getDeserializedValueByFieldName(StepProgress $stepProgress, $fieldName){
        try{
            return $stepProgress->getFieldValueByName($fieldName)->getValue();
        }
        catch(NotFoundException $e){
            return null;
        }
    }

    /**
     * @param string $seminarChoicesPersonalStatement
     * @return ElmSeminarChoices
     */
    public function setSeminarChoicesPersonalStatement($seminarChoicesPersonalStatement)
    {
        $this->seminarChoicesPersonalStatement = $seminarChoicesPersonalStatement;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeminarChoicesPersonalStatement()
    {
        return $this->seminarChoicesPersonalStatement;
    }

    /**
     * @param string $seminarChoicesFirstChoice
     * @return ElmSeminarChoices
     */
    public function setSeminarChoicesFirstChoice($seminarChoicesFirstChoice)
    {
        $this->seminarChoicesFirstChoice = $seminarChoicesFirstChoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeminarChoicesFirstChoice()
    {
        return $this->seminarChoicesFirstChoice;
    }

    /**
     * @param string $seminarChoicesSecondChoice
     * @return ElmSeminarChoices
     */
    public function setSeminarChoicesSecondChoice($seminarChoicesSecondChoice)
    {
        $this->seminarChoicesSecondChoice = $seminarChoicesSecondChoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeminarChoicesSecondChoice()
    {
        return $this->seminarChoicesSecondChoice;
    }

    /**
     * @param string $seminarChoicesThirdChoice
     * @return ElmSeminarChoices
     */
    public function setSeminarChoicesThirdChoice($seminarChoicesThirdChoice)
    {
        $this->seminarChoicesThirdChoice = $seminarChoicesThirdChoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeminarChoicesThirdChoice()
    {
        return $this->seminarChoicesThirdChoice;
    }
}