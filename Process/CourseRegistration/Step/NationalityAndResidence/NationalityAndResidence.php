<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\NationalityAndResidence;

use Symfony\Component\Validator\Constraints as Assert;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class NationalityAndResidence
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $countryOfResidence;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $countryOfBirth;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $primaryNationality;

    /**
     * @var string
     */
    private $secondaryNationality;

    /**
     * @var string
     */
    private $ordinarilyResident;

    /**
     * @var string
     */
    private $eeaOrSwissNational;

    /**
     * @var string
     */
    private $familyMemberEuNational;

    /**
     * @var string
     */
    private $settledInUk;

    /**
     * @var string
     */
    private $grantedRefugeeStatus;

    /**
     * @var string
     */
    private $requireVisa;

    /**
     * @var string
     */
    private $visaStatus;

    /**
     * @param string $countryOfBirth
     * @return NationalityAndResidence
     */
    public function setCountryOfBirth($countryOfBirth)
    {
        $this->countryOfBirth = $countryOfBirth;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryOfBirth()
    {
        return $this->countryOfBirth;
    }

    /**
     * @param string $countryOfResidence
     * @return NationalityAndResidence
     */
    public function setCountryOfResidence($countryOfResidence)
    {
        $this->countryOfResidence = $countryOfResidence;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryOfResidence()
    {
        return $this->countryOfResidence;
    }

    /**
     * @param string $eeaOrSwissNational
     * @return NationalityAndResidence
     */
    public function setEeaOrSwissNational($eeaOrSwissNational)
    {
        $this->eeaOrSwissNational = $eeaOrSwissNational;
        return $this;
    }

    /**
     * @return string
     */
    public function getEeaOrSwissNational()
    {
        return $this->eeaOrSwissNational;
    }

    /**
     * @param string $familyMemberEuNational
     * @return NationalityAndResidence
     */
    public function setFamilyMemberEuNational($familyMemberEuNational)
    {
        $this->familyMemberEuNational = $familyMemberEuNational;
        return $this;
    }

    /**
     * @return string
     */
    public function getFamilyMemberEuNational()
    {
        return $this->familyMemberEuNational;
    }

    /**
     * @param string $grantedRefugeeStatus
     * @return NationalityAndResidence
     */
    public function setGrantedRefugeeStatus($grantedRefugeeStatus)
    {
        $this->grantedRefugeeStatus = $grantedRefugeeStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getGrantedRefugeeStatus()
    {
        return $this->grantedRefugeeStatus;
    }

    /**
     * @param string $ordinarilyResident
     * @return NationalityAndResidence
     */
    public function setOrdinarilyResident($ordinarilyResident)
    {
        $this->ordinarilyResident = $ordinarilyResident;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrdinarilyResident()
    {
        return $this->ordinarilyResident;
    }

    /**
     * @param string $primaryNationality
     * @return NationalityAndResidence
     */
    public function setPrimaryNationality($primaryNationality)
    {
        $this->primaryNationality = $primaryNationality;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimaryNationality()
    {
        return $this->primaryNationality;
    }

    /**
     * @param string $requireVisa
     * @return NationalityAndResidence
     */
    public function setRequireVisa($requireVisa)
    {
        $this->requireVisa = $requireVisa;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequireVisa()
    {
        return $this->requireVisa;
    }

    /**
     * @param string $secondaryNationality
     * @return NationalityAndResidence
     */
    public function setSecondaryNationality($secondaryNationality)
    {
        $this->secondaryNationality = $secondaryNationality;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecondaryNationality()
    {
        return $this->secondaryNationality;
    }

    /**
     * @param string $settledInUk
     * @return NationalityAndResidence
     */
    public function setSettledInUk($settledInUk)
    {
        $this->settledInUk = $settledInUk;
        return $this;
    }

    /**
     * @return string
     */
    public function getSettledInUk()
    {
        return $this->settledInUk;
    }

    /**
     * @param string $visaStatus
     * @return NationalityAndResidence
     */
    public function setVisaStatus($visaStatus)
    {
        $this->visaStatus = $visaStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getVisaStatus()
    {
        return $this->visaStatus;
    }


    /**
     * @param StepProgress $stepProgress
     * @return NationalityAndResidence
     */
    public static function fromStepProgress(StepProgress $stepProgress)
    {
        $instance = new self();

        foreach (get_object_vars($instance) as $fieldName => $currentValue) {
            $setter = 'set' . ucfirst($fieldName);
            try {
                $instance->$setter(
                    $stepProgress->getFieldValueByName($fieldName)->getValue()
                );
            } catch (NotFoundException $e) {
                //Value not present in the step progress - so leave it at its default
            }
        }

        return $instance;
    }
}