<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\LlbAccommodation;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class LlbAccommodation
{

    private $accommodation;

    /**
     * @param mixed $subjectChoices
     * @return LlbAccommodation
     */
    public function setAccommodation($subjectChoices)
    {
        $this->accommodation = $subjectChoices;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccommodation()
    {
        return $this->accommodation;
    }

    /**
     * @param StepProgress $stepProgress
     * @return LlbAccommodation
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setAccommodation($instance->getDeserializedValueByFieldName($stepProgress, 'accommodation', []));
        return $instance;
    }

    /**
     * @param StepProgress $stepProgress
     * @param $fieldName
     * @param null $default
     * @return mixed|null
     */
    private function getDeserializedValueByFieldName(StepProgress $stepProgress, $fieldName, $default = null){
        try{
            return $stepProgress->getFieldValueByName($fieldName)->getValue();
        }
        catch(NotFoundException $e){
            return $default;
        }
    }

    /**
     * @return array
     */
    public function toDataArray()
    {
        $data = [];
        return $data;
    }
}
