<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\ElmAccommodation;

use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Symfony\Component\Validator\Constraints as Assert;

class ElmAccommodation
{
    /**
     * @var int
     */
    private $courseId;

    /**
     * @var string
     */
    private $accommodation;

    /**
     * @var bool
     */
    private $sundayAccommodationAsBool;

    /**
     * @param $courseId
     */
    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }

    /**
     * @param StepProgress $stepProgress
     * @return ElmAccommodation
     */
    public static function fromStepProgress(StepProgress $stepProgress, $courseId){
        $instance = new self($courseId);

        $instance->setAccommodation($instance->getDeserializedValueByFieldName($stepProgress, 'accommodation'));
        $instance->setSundayAccommodation($instance->getDeserializedValueByFieldName($stepProgress, 'sundayAccommodation'));

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
     * @param string $accommodation
     * @return ElmAccommodation
     */
    public function setAccommodation($accommodation)
    {
        $this->accommodation = $accommodation;
        if (!$this->accommodation || strpos($this->accommodation, '-NONE-') !== false) {
            $this->sundayAccommodationAsBool = false;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getAccommodation()
    {
        return $this->accommodation;
    }

    /**
     * @param string $sundayAccommodation
     * @return ElmAccommodation
     */
    public function setSundayAccommodation($sundayAccommodation)
    {
        if (!$sundayAccommodation || strpos($sundayAccommodation, '-NONE-') !== false) {
            $this->sundayAccommodationAsBool = false;
        } else {
            $this->sundayAccommodationAsBool = true;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getSundayAccommodation()
    {
        if ($this->sundayAccommodationAsBool) {
            if (strpos($this->accommodation, '-ENSUITE-')) {
                return $this->getSundayCode(true);
            } else if (strpos($this->accommodation, '-STANDARD-')) {
                return $this->getSundayCode(false);
            }
        }
        return null;
    }

    /**
     * @param string $sundayAccommodation
     * @return ElmAccommodation
     */
    public function setSundayAccommodationAsBool($sundayAccommodation)
    {
        $this->sundayAccommodationAsBool = $sundayAccommodation;
        return $this;
    }

    /**
     * @return string
     */
    public function getSundayAccommodationAsBool()
    {
        return $this->sundayAccommodationAsBool;
    }

    /**
     * @param bool $ensuite
     * @return string
     */
    private function getSundayCode($ensuite = true)
    {
        return sprintf('ACCOMMODATION-SUNDAY-%s-%d', (($ensuite)?'ENSUITE':'STANDARD'), $this->courseId);
    }
}