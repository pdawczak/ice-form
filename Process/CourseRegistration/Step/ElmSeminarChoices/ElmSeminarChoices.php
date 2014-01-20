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
 *  {"Ice\FormBundle\Process\CourseRegistration\Step\ElmSeminarChoices\ElmSeminarChoicesValidator", "isHopeToGainValid"}
 * })
 */
class ElmSeminarChoices
{
    /**
     * @var string
     */
    private $firstChoice;

    /**
     * @var string
     */
    private $secondChoice;

    /**
     * @var string
     */
    private $thirdChoice;

    /**
     * @var string
     */
    private $hopeToGain;

    /**
     * @param StepProgress $stepProgress
     * @return ElmSeminarChoices
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setHopeToGain($instance->getDeserializedValueByFieldName($stepProgress, 'hopeToGain'));
        $instance->setFirstChoice($instance->getDeserializedValueByFieldName($stepProgress, 'firstChoice'));
        $instance->setSecondChoice($instance->getDeserializedValueByFieldName($stepProgress, 'secondChoice'));
        $instance->setThirdChoice($instance->getDeserializedValueByFieldName($stepProgress, 'thirdChoice'));
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
     * @param string $hopeToGain
     * @return ElmSeminarChoices
     */
    public function setHopeToGain($hopeToGain)
    {
        $this->hopeToGain = $hopeToGain;
        return $this;
    }

    /**
     * @return string
     */
    public function getHopeToGain()
    {
        return $this->hopeToGain;
    }

    /**
     * @param string $firstChoice
     * @return ElmSeminarChoices
     */
    public function setFirstChoice($firstChoice)
    {
        $this->firstChoice = $firstChoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstChoice()
    {
        return $this->firstChoice;
    }

    /**
     * @param string $secondChoice
     * @return ElmSeminarChoices
     */
    public function setSecondChoice($secondChoice)
    {
        $this->secondChoice = $secondChoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecondChoice()
    {
        return $this->secondChoice;
    }

    /**
     * @param string $thirdChoice
     * @return ElmSeminarChoices
     */
    public function setThirdChoice($thirdChoice)
    {
        $this->thirdChoice = $thirdChoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getThirdChoice()
    {
        return $this->thirdChoice;
    }
}