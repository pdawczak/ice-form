<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\LawCurrentStudy;

use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class LawCurrentStudy
{
    /**
     * @var string
     * @Assert\NotNull(groups={"default"}, message="This is a required field")
     */
    private $llbEnrolledWithUniversityOfLondon;

    /**
     * @var string
     * @Assert\NotBlank(groups={"not_uol"})
     */
    private $quAltUni;

    /**
     * @param string $llbEnrolledWithUniversityOfLondon
     * @return LawCurrentStudy
     */
    public function setLlbEnrolledWithUniversityOfLondon($llbEnrolledWithUniversityOfLondon)
    {
        $this->llbEnrolledWithUniversityOfLondon = $llbEnrolledWithUniversityOfLondon;
        return $this;
    }

    /**
     * @return string
     */
    public function getLlbEnrolledWithUniversityOfLondon()
    {
        return $this->llbEnrolledWithUniversityOfLondon;
    }

    /**
     * @param string $quAltUni
     * @return LawCurrentStudy
     */
    public function setQuAltUni($quAltUni)
    {
        $this->quAltUni = $quAltUni;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuAltUni()
    {
        return $this->quAltUni;
    }

    /**
     * @param StepProgress $stepProgress
     * @return LawCurrentStudy
     */
    public static function fromStepProgress(StepProgress $stepProgress){
        $instance = new self();
        $instance->setLlbEnrolledWithUniversityOfLondon($instance->getDeserializedValueByFieldName($stepProgress, 'llbEnrolledWithUniversityOfLondon'));
        $instance->setQuAltUni($instance->getDeserializedValueByFieldName($stepProgress, 'quAltUni'));
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
