<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation;

use Symfony\Component\Validator\Constraints as Assert;

class MarketingInformation{
    /**
     * @var array
     * @Assert\NotBlank(groups={"default"}, message="Please select at least one option")
     */
    private $marketingHowHeard = array();

    /**
     * @var string
     * @Assert\NotBlank(groups={"how_heard_other"})
     */
    private $marketingDetail;

    /**
     * @var bool
     * @Assert\NotNull(groups={"default"})
     */
    private $marketingOptIn;

    /**
     * @param string $marketingDetail
     * @return MarketingInformation
     */
    public function setMarketingDetail($marketingDetail)
    {
        $this->marketingDetail = $marketingDetail;
        return $this;
    }

    /**
     * @return string
     */
    public function getMarketingDetail()
    {
        return $this->marketingDetail;
    }

    /**
     * @param array $marketingHowHeard
     * @return MarketingInformation
     */
    public function setMarketingHowHeard($marketingHowHeard)
    {
        $this->marketingHowHeard = $marketingHowHeard;
        return $this;
    }

    /**
     * @return array
     */
    public function getMarketingHowHeard()
    {
        return $this->marketingHowHeard;
    }

    /**
     * @param boolean $marketingOptIn
     * @return MarketingInformation
     */
    public function setMarketingOptIn($marketingOptIn)
    {
        $this->marketingOptIn = $marketingOptIn==true;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getMarketingOptIn()
    {
        return $this->marketingOptIn;
    }
}