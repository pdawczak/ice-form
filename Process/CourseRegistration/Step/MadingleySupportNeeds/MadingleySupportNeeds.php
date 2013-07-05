<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\MadingleySupportNeeds;

use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class MadingleySupportNeeds{
    /**
     * @var string
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
     */
    private $additionalNeeds;

    /**
     * @var string
     * @Assert\NotBlank(groups={"has_additional_needs"})
     */
    private $additionalNeedsDetail;

    /**
     * @var string
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
     */
    private $firstFloorAccess;

    /**
     * @var string
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
     */
    private $shareSupportNeeds;

    /**
     * @param string $additionalNeeds
     * @return MadingleySupportNeeds
     */
    public function setAdditionalNeeds($additionalNeeds)
    {
        $this->additionalNeeds = $additionalNeeds;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalNeeds()
    {
        return $this->additionalNeeds;
    }

    /**
     * @param string $additionalNeedsDetail
     * @return MadingleySupportNeeds
     */
    public function setAdditionalNeedsDetail($additionalNeedsDetail)
    {
        $this->additionalNeedsDetail = $additionalNeedsDetail;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalNeedsDetail()
    {
        return $this->additionalNeedsDetail;
    }

    /**
     * @param string $firstFloorAccess
     * @return MadingleySupportNeeds
     */
    public function setFirstFloorAccess($firstFloorAccess)
    {
        $this->firstFloorAccess = $firstFloorAccess;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstFloorAccess()
    {
        return $this->firstFloorAccess;
    }

    /**
     * @param string $shareSupportNeeds
     * @return MadingleySupportNeeds
     */
    public function setShareSupportNeeds($shareSupportNeeds)
    {
        $this->shareSupportNeeds = $shareSupportNeeds;
        return $this;
    }

    /**
     * @return string
     */
    public function getShareSupportNeeds()
    {
        return $this->shareSupportNeeds;
    }

    /**
     * @param StepProgress $stepProgress
     * @return MadingleySupportNeeds
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setAdditionalNeeds($instance->getDeserializedValueByFieldName($stepProgress, 'additionalNeeds'));
        $instance->setAdditionalNeedsDetail($instance->getDeserializedValueByFieldName($stepProgress, 'additionalNeedsDetail'));
        $instance->setFirstFloorAccess($instance->getDeserializedValueByFieldName($stepProgress, 'firstFloorAccess'));
        $instance->setShareSupportNeeds($instance->getDeserializedValueByFieldName($stepProgress, 'shareSupportNeeds'));
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