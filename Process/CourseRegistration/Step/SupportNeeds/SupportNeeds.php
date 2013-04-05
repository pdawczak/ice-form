<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\SupportNeeds;

use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class SupportNeeds{
    /**
     * @var bool
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
     */
    private $additionalNeeds;

    /**
     * @var string
     * @Assert\NotBlank(groups={"has_additional_needs"})
     */
    private $additionalNeedsDetail;

    /**
     * @var bool
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
     */
    private $firstFloorAccess;

    /**
     * @var bool
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
     */
    private $shareSupportNeeds;


    /**
     * @param boolean $additionalNeeds
     * @return SupportNeeds
     */
    public function setAdditionalNeeds($additionalNeeds)
    {
        $this->additionalNeeds = $additionalNeeds===null?null:($additionalNeeds==true);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAdditionalNeeds()
    {
        return $this->additionalNeeds;
    }

    /**
     * @param string $additionalNeedsDetail
     * @return SupportNeeds
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
     * @param boolean $firstFloorAccess
     * @return SupportNeeds
     */
    public function setFirstFloorAccess($firstFloorAccess)
    {
        $this->firstFloorAccess = $firstFloorAccess===null?null:($firstFloorAccess==true);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getFirstFloorAccess()
    {
        return $this->firstFloorAccess;
    }

    /**
     * @param boolean $shareSupportNeeds
     * @return SupportNeeds
     */
    public function setShareSupportNeeds($shareSupportNeeds)
    {
        $this->shareSupportNeeds = $shareSupportNeeds===null?null:($shareSupportNeeds==true);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getShareSupportNeeds()
    {
        return $this->shareSupportNeeds;
    }

    /**
     * @param StepProgress $stepProgress
     * @return SupportNeeds
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
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