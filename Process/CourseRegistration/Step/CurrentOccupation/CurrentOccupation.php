<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\CurrentOccupation;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class CurrentOccupation
{
    /**
     * @var string
     * @Assert\NotNull(message="This is a required field")
     */
    private $currentOccupation;

    /**
     * @var string
     * @Assert\NotNull(message="This is a required field")
     */
    private $companyOrInstitution;

    /**
     * @var string
     */
    private $institutionAddress1;

    /**
     * @var string
     */
    private $institutionAddress2;

    /**
     * @var string
     */
    private $institutionAddress3;

    /**
     * @var string
     */
    private $institutionTown;

    /**
     * @var string
     */
    private $institutionCounty;

    /**
     * @var string
     */
    private $institutionPostCode;

    /**
     * @var string
     */
    private $institutionCountry;


    /**
     * @param string $currentOccupation
     * @return CurrentOccupation
     */
    public function setCurrentOccupation($currentOccupation)
    {
        $this->currentOccupation = $currentOccupation;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentOccupation()
    {
        return $this->currentOccupation;
    }

    /**
     * @param StepProgress $stepProgress
     * @return CurrentOccupation
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setCurrentOccupation($instance->getDeserializedValueByFieldName($stepProgress, 'currentOccupation'));
        $instance->setCompanyOrInstitution($instance->getDeserializedValueByFieldName($stepProgress, 'companyOrInstitution'));
        $instance->setInstitutionAddress1($instance->getDeserializedValueByFieldName($stepProgress, 'institutionAddress1'));
        $instance->setInstitutionAddress2($instance->getDeserializedValueByFieldName($stepProgress, 'institutionAddress2'));
        $instance->setInstitutionAddress3($instance->getDeserializedValueByFieldName($stepProgress, 'institutionAddress3'));
        $instance->setInstitutionTown($instance->getDeserializedValueByFieldName($stepProgress, 'institutionTown'));
        $instance->setInstitutionPostCode($instance->getDeserializedValueByFieldName($stepProgress, 'institutionPostCode'));
        $instance->setInstitutionCounty($instance->getDeserializedValueByFieldName($stepProgress, 'institutionCounty'));
        $instance->setInstitutionCountry($instance->getDeserializedValueByFieldName($stepProgress, 'institutionCountry'));
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
     * @param string $companyOrInstitution
     * @return CurrentOccupation
     */
    public function setCompanyOrInstitution($companyOrInstitution)
    {
        $this->companyOrInstitution = $companyOrInstitution;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompanyOrInstitution()
    {
        return $this->companyOrInstitution;
    }

    /**
     * @param string $institutionAddress1
     * @return CurrentOccupation
     */
    public function setInstitutionAddress1($institutionAddress1)
    {
        $this->institutionAddress1 = $institutionAddress1;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitutionAddress1()
    {
        return $this->institutionAddress1;
    }

    /**
     * @param string $institutionAddress2
     * @return CurrentOccupation
     */
    public function setInstitutionAddress2($institutionAddress2)
    {
        $this->institutionAddress2 = $institutionAddress2;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitutionAddress2()
    {
        return $this->institutionAddress2;
    }

    /**
     * @param string $institutionAddress3
     * @return CurrentOccupation
     */
    public function setInstitutionAddress3($institutionAddress3)
    {
        $this->institutionAddress3 = $institutionAddress3;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitutionAddress3()
    {
        return $this->institutionAddress3;
    }

    /**
     * @param string $institutionCountry
     * @return CurrentOccupation
     */
    public function setInstitutionCountry($institutionCountry)
    {
        $this->institutionCountry = $institutionCountry;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitutionCountry()
    {
        return $this->institutionCountry;
    }

    /**
     * @param string $institutionCounty
     * @return CurrentOccupation
     */
    public function setInstitutionCounty($institutionCounty)
    {
        $this->institutionCounty = $institutionCounty;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitutionCounty()
    {
        return $this->institutionCounty;
    }

    /**
     * @param string $institutionPostCode
     * @return CurrentOccupation
     */
    public function setInstitutionPostCode($institutionPostCode)
    {
        $this->institutionPostCode = $institutionPostCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitutionPostCode()
    {
        return $this->institutionPostCode;
    }

    /**
     * @param string $institutionTown
     * @return CurrentOccupation
     */
    public function setInstitutionTown($institutionTown)
    {
        $this->institutionTown = $institutionTown;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitutionTown()
    {
        return $this->institutionTown;
    }
}
