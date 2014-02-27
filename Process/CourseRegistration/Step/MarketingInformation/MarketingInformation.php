<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation;

use Symfony\Component\Validator\Constraints as Assert;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class MarketingInformation{
    /**
     * @var array
     * @Assert\NotBlank(message="Please select at least one option")
     */
    private $marketingHowHeard = array();

    /**
     * @var string
     */
    private $marketingDetail;

    /**
     * @var bool
     * @Assert\NotNull()
     */
    private $marketingOptIn;

    /**
     * @var string
     */
    private $bookingCode;

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

    /**
     * @param StepProgress $stepProgress
     * @return MarketingInformation
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setMarketingHowHeard($instance->getDeserializedValueByFieldName($stepProgress, 'marketingHowHeard'));
        $instance->setMarketingDetail($instance->getDeserializedValueByFieldName($stepProgress, 'marketingDetail'));
        $instance->setBookingCode($instance->getDeserializedValueByFieldName($stepProgress, 'bookingCode'));
        $instance->setMarketingOptIn($instance->getDeserializedValueByFieldName($stepProgress, 'marketingOptIn'));
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
     * @param string $bookingCode
     * @return MarketingInformation
     */
    public function setBookingCode($bookingCode)
    {
        $this->bookingCode = $bookingCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getBookingCode()
    {
        return $this->bookingCode;
    }

    /**
     * @return array
     */
    public function getValidCodes()
    {
        return [
            'DISCOUNT-CC2014'
        ];
    }
}