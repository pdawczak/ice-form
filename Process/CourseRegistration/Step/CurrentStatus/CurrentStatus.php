<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\CurrentStatus;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class CurrentStatus
{
    /**
     * @var string
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
     */
    private $currentStatus;

    /**
     * @var string
     * @Assert\NotBlank(groups={"status_student"})
     */
    private $courseTitle;

    /**
     * @var string
     * @Assert\NotBlank(groups={"status_student"})
     */
    private $courseStartDate;

    /**
     * @var string
     * @Assert\NotBlank(groups={"status_other"})
     */
    private $specifyOther;

    /**
     * @var string
     * @Assert\NotBlank(groups={"status_professional"})
     */
    private $position;

    /**
     * @var string
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
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
     * @param string $currentStatus
     * @return CurrentStatus
     */
    public function setCurrentStatus($currentStatus)
    {
        $this->currentStatus = $currentStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * @param string $courseTitle
     * @return CurrentStatus
     */
    public function setCourseTitle($courseTitle)
    {
        $this->courseTitle = $courseTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getCourseTitle()
    {
        return $this->courseTitle;
    }

    /**
     * @param StepProgress $stepProgress
     * @return CurrentStatus
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        
        $instance->setCurrentStatus($instance->getDeserializedValueByFieldName($stepProgress, 'currentStatus'));
        $instance->setSpecifyOther($instance->getDeserializedValueByFieldName($stepProgress, 'specifyOther'));
        $instance->setPosition($instance->getDeserializedValueByFieldName($stepProgress, 'position'));

        $instance->setCompanyOrInstitution($instance->getDeserializedValueByFieldName($stepProgress, 'companyOrInstitution'));
        $instance->setInstitutionAddress1($instance->getDeserializedValueByFieldName($stepProgress, 'institutionAddress1'));
        $instance->setInstitutionAddress2($instance->getDeserializedValueByFieldName($stepProgress, 'institutionAddress2'));
        $instance->setInstitutionAddress3($instance->getDeserializedValueByFieldName($stepProgress, 'institutionAddress3'));
        $instance->setInstitutionTown($instance->getDeserializedValueByFieldName($stepProgress, 'institutionTown'));
        $instance->setInstitutionPostCode($instance->getDeserializedValueByFieldName($stepProgress, 'institutionPostCode'));
        $instance->setInstitutionCounty($instance->getDeserializedValueByFieldName($stepProgress, 'institutionCounty'));
        $instance->setInstitutionCountry($instance->getDeserializedValueByFieldName($stepProgress, 'institutionCountry'));
        
        $instance->setCurrentStatus($instance->getDeserializedValueByFieldName($stepProgress, 'currentStatus'));
        $instance->setCourseTitle($instance->getDeserializedValueByFieldName($stepProgress, 'courseTitle'));
        $instance->setCourseStartDate($instance->getDeserializedValueByFieldName($stepProgress, 'courseStartDate'));
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
     * @param string $specifyOther
     * @return CurrentStatus
     */
    public function setSpecifyOther($specifyOther)
    {
        $this->specifyOther = $specifyOther;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpecifyOther()
    {
        return $this->specifyOther;
    }

    /**
     * @param string $courseStartDate
     * @return CurrentStatus
     */
    public function setCourseStartDate($courseStartDate)
    {
        $this->courseStartDate = $courseStartDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getCourseStartDate()
    {
        return $this->courseStartDate;
    }

    /**
     * @param string $companyOrInstitution
     * @return CurrentStatus
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
     * @return CurrentStatus
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
     * @return CurrentStatus
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
     * @return CurrentStatus
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
     * @return CurrentStatus
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
     * @return CurrentStatus
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
     * @return CurrentStatus
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
     * @return CurrentStatus
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

    /**
     * @param string $position
     * @return CurrentStatus
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }
}
