<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\LlbSubjectChoices;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class LlbSubjectChoices
{

    private $subjectChoices;

    /**
     * @param mixed $subjectChoices
     * @return LlbSubjectChoices
     */
    public function setSubjectChoices($subjectChoices)
    {
        $this->subjectChoices = $subjectChoices;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubjectChoices()
    {
        return $this->subjectChoices;
    }

    /**
     * @param StepProgress $stepProgress
     * @return LlbSubjectChoices
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setSubjectChoices($instance->getDeserializedValueByFieldName($stepProgress, 'subjectChoices', []));
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
