<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\EnglishLanguageSelfCertify;

use Symfony\Component\Validator\Constraints as Assert;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;

class EnglishLanguageSelfCertify
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $englishFirstLanguage;

    /**
     * @param string $englishFirstLanguage
     * @return EnglishLanguageSelfCertify
     */
    public function setEnglishFirstLanguage($englishFirstLanguage)
    {
        $this->englishFirstLanguage = $englishFirstLanguage;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnglishFirstLanguage()
    {
        return $this->englishFirstLanguage;
    }

    /**
     * @param StepProgress $stepProgress
     * @return TermsAndConditions
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
