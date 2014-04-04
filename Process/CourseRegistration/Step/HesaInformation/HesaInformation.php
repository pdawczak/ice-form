<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\HesaInformation;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class HesaInformation
{
    /**
     * @var string
     * @Assert\NotBlank(groups={"ask_disability"})
     */
    private $disabilityListed;

    /**
     * @var string
     * @Assert\NotBlank(groups={"ask_dsa"})
     */
    private $inReceiptOfDisabledStudentsAllowance;

    /**
     * @var string
     */
    private $hesaEthnicOrigin;

    /**
     * @var string
     */
    private $hesaPreviouslyStudiedAtDegreeLevel;

    /**
     * @var string
     */
    private $hesaParentalQualifications;

    /**
     * @var string
     */
    private $hesaHighestQualification;

    /**
     * @var string
     */
    private $hesaMostRecentEducationInstitutionType;


    /**
     * @var string
     */
    private $hesaMostRecentEducationInstitutionName;

    /**
     * @var string
     */
    private $hesaFeeSource;

    /**
     * @var string
     * @Assert\NotBlank(groups={"fee_source_employer"})
     */
    private $hesaFeesEmployerType;

    /**
     * @var string
     */
    private $hesaCareOrder;

    /**
     * @param string $hesaEthnicOrigin
     * @return HesaInformation
     */
    public function setHesaEthnicOrigin($hesaEthnicOrigin)
    {
        $this->hesaEthnicOrigin = $hesaEthnicOrigin;
        return $this;
    }

    /**
     * @return string
     */
    public function getHesaEthnicOrigin()
    {
        return $this->hesaEthnicOrigin;
    }

    /**
     * @param string $hesaPreviouslyStudiedAtDegreeLevel
     * @return HesaInformation
     */
    public function setHesaPreviouslyStudiedAtDegreeLevel($hesaPreviouslyStudiedAtDegreeLevel)
    {
        $this->hesaPreviouslyStudiedAtDegreeLevel = $hesaPreviouslyStudiedAtDegreeLevel;
        return $this;
    }

    /**
     * @return string
     */
    public function getHesaPreviouslyStudiedAtDegreeLevel()
    {
        return $this->hesaPreviouslyStudiedAtDegreeLevel;
    }

    /**
     * @param string $hesaParentalQualifications
     * @return HesaInformation
     */
    public function setHesaParentalQualifications($hesaParentalQualifications)
    {
        $this->hesaParentalQualifications = $hesaParentalQualifications;
        return $this;
    }

    /**
     * @return string
     */
    public function getHesaParentalQualifications()
    {
        return $this->hesaParentalQualifications;
    }

    /**
     * @param string $hesaFeeSource
     * @return HesaInformation
     */
    public function setHesaFeeSource($hesaFeeSource)
    {
        $this->hesaFeeSource = $hesaFeeSource;
        return $this;
    }

    /**
     * @return string
     */
    public function getHesaFeeSource()
    {
        return $this->hesaFeeSource;
    }

    /**
     * @param string $hesaFeesEmployerName
     * @return HesaInformation
     */
    public function setHesaFeesEmployerType($hesaFeesEmployerName)
    {
        $this->hesaFeesEmployerType = $hesaFeesEmployerName;
        return $this;
    }

    /**
     * @return string
     */
    public function getHesaFeesEmployerType()
    {
        return $this->hesaFeesEmployerType;
    }

    /**
     * @param string $hesaHighestQualification
     * @return HesaInformation
     */
    public function setHesaHighestQualification($hesaHighestQualification)
    {
        $this->hesaHighestQualification = $hesaHighestQualification;
        return $this;
    }

    /**
     * @return string
     */
    public function getHesaHighestQualification()
    {
        return $this->hesaHighestQualification;
    }

    /**
     * @param string $hesaMostRecentEducationInstitutionName
     * @return HesaInformation
     */
    public function setHesaMostRecentEducationInstitutionName($hesaMostRecentEducationInstitutionName)
    {
        $this->hesaMostRecentEducationInstitutionName = $hesaMostRecentEducationInstitutionName;
        return $this;
    }

    /**
     * @return string
     */
    public function getHesaMostRecentEducationInstitutionName()
    {
        return $this->hesaMostRecentEducationInstitutionName;
    }

    /**
     * @param string $hesaMostRecentEducationInstitutionType
     * @return HesaInformation
     */
    public function setHesaMostRecentEducationInstitutionType($hesaMostRecentEducationInstitutionType)
    {
        $this->hesaMostRecentEducationInstitutionType = $hesaMostRecentEducationInstitutionType;
        return $this;
    }

    /**
     * @return string
     */
    public function getHesaMostRecentEducationInstitutionType()
    {
        return $this->hesaMostRecentEducationInstitutionType;
    }

    /**
     * @param string $disabilityListed
     * @return HesaInformation
     */
    public function setDisabilityListed($disabilityListed)
    {
        $this->disabilityListed = $disabilityListed;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisabilityListed()
    {
        return $this->disabilityListed;
    }

    /**
     * @param string $inReceiptOfDisabledStudentsAllowance
     * @return HesaInformation
     */
    public function setInReceiptOfDisabledStudentsAllowance($inReceiptOfDisabledStudentsAllowance)
    {
        $this->inReceiptOfDisabledStudentsAllowance = $inReceiptOfDisabledStudentsAllowance;
        return $this;
    }

    /**
     * @return string
     */
    public function getInReceiptOfDisabledStudentsAllowance()
    {
        return $this->inReceiptOfDisabledStudentsAllowance;
    }

    /**
     * @param string $hesaCareOrder
     * @return HesaInformation
     */
    public function setHesaCareOrder($hesaCareOrder)
    {
        $this->hesaCareOrder = $hesaCareOrder;
        return $this;
    }

    /**
     * @return string
     */
    public function getHesaCareOrder()
    {
        return $this->hesaCareOrder;
    }

    /**
     * @param StepProgress $stepProgress
     * @return HesaInformation
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