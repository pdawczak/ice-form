<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\DisabilityAndSupportNeeds;

use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class DisabilityAndSupportNeeds
{
    /**
     * @var string
     * @Assert\NotBlank(groups={"enable_hesa"})
     */
    private $disabilityListed;

    /**
     * @var string
     * @Assert\NotBlank(groups={"enable_hesa"})
     */
    private $inReceiptOfDisabledStudentsAllowance;

    /**
     * @var string
     * @Assert\NotBlank(groups={"Default"})
     */
    private $additionalNeeds;

    /**
     * @var string
     * @Assert\NotBlank(groups={"enable_additional_needs_detail"})
     */
    private $additionalNeedsDetail;

    /**
     * @var string
     * @Assert\NotBlank(groups={"Default"})
     */
    private $firstFloorAccess;

    /**
     * @var string
     * @Assert\NotBlank(groups={"enable_share_support_needs"})
     */
    private $shareSupportNeeds;

    /**
     * @param string $additionalNeeds
     * @return DisabilityAndSupportNeeds
     */
    public function setAdditionalNeeds($additionalNeeds)
    {
        $this->additionalNeeds = $additionalNeeds;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalNeeds()
    {
        return $this->additionalNeeds;
    }

    /**
     * @param string $additionalNeedsDetail
     * @return DisabilityAndSupportNeeds
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
     * @param string $disabilityListed
     * @return DisabilityAndSupportNeeds
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
     * @param string $firstFloorAccess
     * @return DisabilityAndSupportNeeds
     */
    public function setFirstFloorAccess($firstFloorAccess)
    {
        $this->firstFloorAccess = $firstFloorAccess;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstFloorAccess()
    {
        return $this->firstFloorAccess;
    }

    /**
     * @param string $inReceiptOfDisabledStudentsAllowance
     * @return DisabilityAndSupportNeeds
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
     * @param string $shareSupportNeeds
     * @return DisabilityAndSupportNeeds
     */
    public function setShareSupportNeeds($shareSupportNeeds)
    {
        $this->shareSupportNeeds = $shareSupportNeeds;
        return $this;
    }

    /**
     * @return string
     */
    public function getShareSupportNeeds()
    {
        return $this->shareSupportNeeds;
    }

    /**
     * @param StepProgress $stepProgress
     * @return DisabilityAndSupportNeeds
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