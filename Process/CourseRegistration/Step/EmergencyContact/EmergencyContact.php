<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\EmergencyContact;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class EmergencyContact{
    /** @var string */
    private $name;

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

    /**
     * @param string $name
     * @return EmergencyContact
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $address1
     * @return EmergencyContact
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
     * @return EmergencyContact
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
     * @return EmergencyContact
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
     * @return EmergencyContact
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
     * @return EmergencyContact
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
     * @return EmergencyContact
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
     * @return EmergencyContact
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
     * @return EmergencyContact
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
     * @return EmergencyContact
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setName($instance->getDeserializedValueByFieldName($stepProgress, 'emergency_name'));
        $instance->setAddress1($instance->getDeserializedValueByFieldName($stepProgress, 'emergency_address1'));
        $instance->setAddress2($instance->getDeserializedValueByFieldName($stepProgress, 'emergency_address2'));
        $instance->setAddress3($instance->getDeserializedValueByFieldName($stepProgress, 'emergency_address3'));
        $instance->setTown($instance->getDeserializedValueByFieldName($stepProgress, 'emergency_town'));
        $instance->setCounty($instance->getDeserializedValueByFieldName($stepProgress, 'emergency_county'));
        $instance->setPostCode($instance->getDeserializedValueByFieldName($stepProgress, 'emergency_postCode'));
        $instance->setCountry($instance->getDeserializedValueByFieldName($stepProgress, 'emergency_country'));
        $instance->setTelephone($instance->getDeserializedValueByFieldName($stepProgress, 'emergency_telephone'));
        return $instance;
    }

    /**
     * @param User $user
     * @return EmergencyContact
     */
    public static function fromUser(User $user){
        $instance = new self();
        $instance->setName($user->getAttributeValueByName('emergency_name'));
        $instance->setAddress1($user->getAttributeValueByName('emergency_address1'));
        $instance->setAddress2($user->getAttributeValueByName('emergency_address2'));
        $instance->setAddress3($user->getAttributeValueByName('emergency_address3'));
        $instance->setTown($user->getAttributeValueByName('emergency_town'));
        $instance->setCounty($user->getAttributeValueByName('emergency_county'));
        $instance->setPostCode($user->getAttributeValueByName('emergency_postCode'));
        $instance->setCountry($user->getAttributeValueByName('emergency_country'));
        $instance->setTelephone($user->getAttributeValueByName('emergency_telephone'));
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
