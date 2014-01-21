<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\LawCurrentStatus;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class LawCurrentStatus
{
    /**
     * @var string
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
     */
    private $currentStatus;

    /**
     * @var string
     */
    private $currentQualifications;

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
     * @var int
     * @Assert\NotBlank(groups={"default"})
     */
    private $oneYearOfStudy;

    /**
     * @var string
     * @Assert\NotBlank(groups={"status_other"})
     */
    private $specifyOther;

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
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        
        $instance->setCurrentStatus($instance->getDeserializedValueByFieldName($stepProgress, 'currentStatus'));
        $instance->setSpecifyOther($instance->getDeserializedValueByFieldName($stepProgress, 'specifyOther'));
        $instance->setCurrentQualifications($instance->getDeserializedValueByFieldName($stepProgress, 'currentQualifications'));

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
        $instance->setOneYearOfStudy($instance->getDeserializedValueByFieldName($stepProgress, 'oneYearOfStudy'));
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
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
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
     * @param int $oneYearStudy
     * @return LawCurrentStatus
     */
    public function setOneYearOfStudy($oneYearStudy)
    {
        $this->oneYearOfStudy = $oneYearStudy;
        return $this;
    }

    /**
     * @return int
     */
    public function getOneYearOfStudy()
    {
        return $this->oneYearOfStudy;
    }

    /**
     * @param string $currentQualifications
     * @return LawCurrentStatus
     */
    public function setCurrentQualifications($currentQualifications)
    {
        $this->currentQualifications = $currentQualifications;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentQualifications()
    {
        return $this->currentQualifications;
    }

    /**
     * @param string $companyOrInstitution
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
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
     * @return LawCurrentStatus
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
