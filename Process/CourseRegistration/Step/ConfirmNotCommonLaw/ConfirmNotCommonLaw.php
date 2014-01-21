<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\ConfirmNotCommonLaw;

use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class ConfirmNotCommonLaw
{
    /**
     * @var bool
     * @Assert\True(message="You must confirm to continue")
     */
    private $confirmNotCommonLaw;

    /**
     * @param mixed $confirmNotCommonLaw
     * @return ConfirmNotCommonLaw
     */
    public function setConfirmNotCommonLaw($confirmNotCommonLaw)
    {
        $this->confirmNotCommonLaw = $confirmNotCommonLaw;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfirmNotCommonLaw()
    {
        return $this->confirmNotCommonLaw;
    }

    public static function fromStepProgress(StepProgress $progress)
    {
        $instance = new self();
        $instance->setConfirmNotCommonLaw($instance->getDeserializedValueByFieldName($progress, 'confirmNotCommonLaw'));
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
}