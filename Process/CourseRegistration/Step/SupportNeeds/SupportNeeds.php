<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\SupportNeeds;

use Ice\JanusClientBundle\Entity\User;

class SupportNeeds{
    /**
     * @var bool
     */
    private $additionalNeeds;

    /**
     * @var string
     */
    private $additionalNeedsDetail;

    /**
     * @var bool
     */
    private $firstFloorAccess;

    /**
     * @var bool
     */
    private $shareSupportNeeds;


    /**
     * @param boolean $additionalNeeds
     * @return SupportNeeds
     */
    public function setAdditionalNeeds($additionalNeeds)
    {
        $this->additionalNeeds = $additionalNeeds;
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
        $this->firstFloorAccess = $firstFloorAccess;
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
        $this->shareSupportNeeds = $shareSupportNeeds;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getShareSupportNeeds()
    {
        return $this->shareSupportNeeds;
    }
}