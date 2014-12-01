<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\AttendeeDetailsWithTermAddress;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class AttendeeDetailsWithTermAddress {
    /** @var string */
    private $address1;

    /** @var string */
    private $address2;

    /** @var string */
    private $address3;

    /** @var string */
    private $town;

    /** @var string */
    private $county;

    /** @var string */
    private $country;

    /** @var string */
    private $postCode;

    /** @var string */
    private $telephone;

    /** @var bool */
    private $termTimeAddressTheSame = true;

    /** @var string */
    private $termTimeAddress1;

    /** @var string */
    private $termTimeAddress2;

    /** @var string */
    private $termTimeAddress3;

    /** @var string */
    private $termTimeTown;

    /** @var string */
    private $termTimeCounty;

    /** @var string */
    private $termTimeCountry;

    /** @var string */
    private $termTimePostCode;

    /** @var string */
    private $termTimeTelephone;

    /**
     * @param string $address1
     * @return AttendeeDetails
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address2
     * @return AttendeeDetails
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address3
     * @return AttendeeDetails
     */
    public function setAddress3($address3)
    {
        $this->address3 = $address3;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress3()
    {
        return $this->address3;
    }

    /**
     * @param string $county
     * @return AttendeeDetails
     */
    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }

    /**
     * @return string
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param string $town
     * @return AttendeeDetails
     */
    public function setTown($town)
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param string $country
     * @return AttendeeDetails
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $postCode
     * @return AttendeeDetails
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param string $telephone
     * @return AttendeeDetails
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param StepProgress $stepProgress
     * @return AttendeeDetails
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setAddress1($instance->getDeserializedValueByFieldName($stepProgress, 'address1'));
        $instance->setAddress2($instance->getDeserializedValueByFieldName($stepProgress, 'address2'));
        $instance->setAddress3($instance->getDeserializedValueByFieldName($stepProgress, 'address3'));
        $instance->setTown($instance->getDeserializedValueByFieldName($stepProgress, 'town'));
        $instance->setCounty($instance->getDeserializedValueByFieldName($stepProgress, 'county'));
        $instance->setPostCode($instance->getDeserializedValueByFieldName($stepProgress, 'postCode'));
        $instance->setCountry($instance->getDeserializedValueByFieldName($stepProgress, 'country'));
        $instance->setTelephone($instance->getDeserializedValueByFieldName($stepProgress, 'telephone'));

        $instance->setTermTimeAddressTheSame($instance->getDeserializedValueByFieldName($stepProgress, 'termTimeAddressTheSame'));
        $instance->setTermTimeAddress1($instance->getDeserializedValueByFieldName($stepProgress, 'termTimeAddress1'));
        $instance->setTermTimeAddress2($instance->getDeserializedValueByFieldName($stepProgress, 'termTimeAddress2'));
        $instance->setTermTimeAddress3($instance->getDeserializedValueByFieldName($stepProgress, 'termTimeAddress3'));
        $instance->setTermTimeTown($instance->getDeserializedValueByFieldName($stepProgress, 'termTimeTown'));
        $instance->setTermTimeCounty($instance->getDeserializedValueByFieldName($stepProgress, 'termTimeCounty'));
        $instance->setTermTimePostCode($instance->getDeserializedValueByFieldName($stepProgress, 'termTimePostCode'));
        $instance->setTermTimeCountry($instance->getDeserializedValueByFieldName($stepProgress, 'termTimeCountry'));
        $instance->setTermTimeTelephone($instance->getDeserializedValueByFieldName($stepProgress, 'termTimeTelephone'));
        return $instance;
    }

    /**
     * @param User $user
     * @return AttendeeDetails
     */
    public static function fromUser(User $user){
        $instance = new self();
        $instance->setAddress1($user->getAttributeValueByName('address1'));
        $instance->setAddress2($user->getAttributeValueByName('address2'));
        $instance->setAddress3($user->getAttributeValueByName('address3'));
        $instance->setTown($user->getAttributeValueByName('town'));
        $instance->setCounty($user->getAttributeValueByName('county'));
        $instance->setPostCode($user->getAttributeValueByName('postCode'));
        $instance->setCountry($user->getAttributeValueByName('country'));
        $instance->setTelephone($user->getAttributeValueByName('telephone'));

        // Converting values 'Y'/'N' to boolean => true/false
        $instance->setTermTimeAddressTheSame($user->getAttributeValueByName('termTimeAddressTheSame') == 'Y');
        $instance->setTermTimeAddress1($user->getAttributeValueByName('termTimeAddress1'));
        $instance->setTermTimeAddress2($user->getAttributeValueByName('termTimeAddress2'));
        $instance->setTermTimeAddress3($user->getAttributeValueByName('termTimeAddress3'));
        $instance->setTermTimeTown($user->getAttributeValueByName('termTimeTown'));
        $instance->setTermTimeCounty($user->getAttributeValueByName('termTimeCounty'));
        $instance->setTermTimePostCode($user->getAttributeValueByName('termTimePostCode'));
        $instance->setTermTimeCountry($user->getAttributeValueByName('termTimeCountry'));
        $instance->setTermTimeTelephone($user->getAttributeValueByName('termTimeTelephone'));
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
     * @return boolean
     */
    public function getTermTimeAddressTheSame()
    {
        return $this->termTimeAddressTheSame;
    }

    /**
     * @param boolean $termTimeAddressTheSame
     */
    public function setTermTimeAddressTheSame($termTimeAddressTheSame)
    {
        $this->termTimeAddressTheSame = $termTimeAddressTheSame;
    }

    /**
     * @return string
     */
    public function getTermTimeAddress1()
    {
        return $this->termTimeAddress1;
    }

    /**
     * @param string $termTimeAddress1
     */
    public function setTermTimeAddress1($termTimeAddress1)
    {
        $this->termTimeAddress1 = $termTimeAddress1;
    }

    /**
     * @return string
     */
    public function getTermTimeAddress2()
    {
        return $this->termTimeAddress2;
    }

    /**
     * @param string $termTimeAddress2
     */
    public function setTermTimeAddress2($termTimeAddress2)
    {
        $this->termTimeAddress2 = $termTimeAddress2;
    }

    /**
     * @return string
     */
    public function getTermTimeAddress3()
    {
        return $this->termTimeAddress3;
    }

    /**
     * @param string $termTimeAddress3
     */
    public function setTermTimeAddress3($termTimeAddress3)
    {
        $this->termTimeAddress3 = $termTimeAddress3;
    }

    /**
     * @return string
     */
    public function getTermTimeTown()
    {
        return $this->termTimeTown;
    }

    /**
     * @param string $termTimeTown
     */
    public function setTermTimeTown($termTimeTown)
    {
        $this->termTimeTown = $termTimeTown;
    }

    /**
     * @return string
     */
    public function getTermTimeCounty()
    {
        return $this->termTimeCounty;
    }

    /**
     * @param string $termTimeCounty
     */
    public function setTermTimeCounty($termTimeCounty)
    {
        $this->termTimeCounty = $termTimeCounty;
    }

    /**
     * @return string
     */
    public function getTermTimeCountry()
    {
        return $this->termTimeCountry;
    }

    /**
     * @param string $termTimeCountry
     */
    public function setTermTimeCountry($termTimeCountry)
    {
        $this->termTimeCountry = $termTimeCountry;
    }

    /**
     * @return string
     */
    public function getTermTimePostCode()
    {
        return $this->termTimePostCode;
    }

    /**
     * @param string $termTimePostCode
     */
    public function setTermTimePostCode($termTimePostCode)
    {
        $this->termTimePostCode = $termTimePostCode;
    }

    /**
     * @return string
     */
    public function getTermTimeTelephone()
    {
        return $this->termTimeTelephone;
    }

    /**
     * @param string $termTimeTelephone
     */
    public function setTermTimeTelephone($termTimeTelephone)
    {
        $this->termTimeTelephone = $termTimeTelephone;
    }


}
