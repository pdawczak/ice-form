<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

use Ice\MinervaClientBundle\Entity\StepProgress;
use Symfony\Component\Validator\Constraints as Assert;

class WeekendAccommodation
{
    /**
     * @var string
     */
    private $accommodation;

    /**
     * @var string
     */
    private $adaptedBedroomRequired;

    /**
     * @var string
     */
    private $accommodationRequirements;

    /**
     * @var string
     */
    private $bedAndBreakfastAccommodation;

    /**
     * @var string
     */
    private $accommodationSharingWith;

    /**
     * @var string
     */
    private $platter;

    /**
     * @var string
     */
    private $platterOption;

    /**
     * @var string
     */
    private $dietaryRequirements;

    public function setAccommodation($accommodation)
    {
        $this->accommodation = $accommodation;
        return $this;
    }

    public function getAccommodation()
    {
        return $this->accommodation;
    }

    /**
     * @param string $bedAndBreakfastAccommodation
     *
     * @return WeekendAccommodation
     */
    public function setBedAndBreakfastAccommodation($bedAndBreakfastAccommodation)
    {
        $this->bedAndBreakfastAccommodation = $bedAndBreakfastAccommodation;
        return $this;
    }

    /**
     * @return string
     */
    public function getBedAndBreakfastAccommodation()
    {
        return $this->bedAndBreakfastAccommodation;
    }

    /**
     * @param  $platter
     *
     * @return WeekendAccommodation
     */
    public function setPlatter($platter)
    {
        $this->platter = $platter;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlatter()
    {
        return $this->platter;
    }

    /**
     * @param string $accommodationRequirements
     * @return WeekendAccommodation
     */
    public function setAccommodationRequirements($accommodationRequirements)
    {
        $this->accommodationRequirements = $accommodationRequirements;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccommodationRequirements()
    {
        return $this->accommodationRequirements;
    }

    /**
     * @param string $adaptedBedroomRequired
     * @return WeekendAccommodation
     */
    public function setAdaptedBedroomRequired($adaptedBedroomRequired)
    {
        $this->adaptedBedroomRequired = $adaptedBedroomRequired;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdaptedBedroomRequired()
    {
        return $this->adaptedBedroomRequired;
    }

    /**
     * @param array $accommodationRequirementsGroup
     * @return $this
     */
    public function setAccommodationRequirementsGroup($accommodationRequirementsGroup)
    {
        if (isset($accommodationRequirementsGroup['adaptedBedroomRequired'])) {
            $this->setAdaptedBedroomRequired(
                $accommodationRequirementsGroup['adaptedBedroomRequired']?
                $accommodationRequirementsGroup['adaptedBedroomRequired']:
                'N'
            );
        }
        if (isset($accommodationRequirementsGroup['accommodationRequirements'])) {
            $this->setAccommodationRequirements($accommodationRequirementsGroup['accommodationRequirements']);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getAccommodationRequirementsGroup()
    {
        return [
            'adaptedBedroomRequired' => $this->getAdaptedBedroomRequired()?1:0,
            'accommodationRequirements' => $this->getAccommodationRequirements()
        ];
    }

    /**
     * @param  $accommodationSharingWith
     *
     * @return WeekendAccommodation
     */
    public function setAccommodationSharingWith($accommodationSharingWith)
    {
        $this->accommodationSharingWith = $accommodationSharingWith;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccommodationSharingWith()
    {
        return $this->accommodationSharingWith;
    }

    /**
     * @param string $dietaryRequirements
     *
     * @return WeekendAccommodation
     */
    public function setDietaryRequirements($dietaryRequirements)
    {
        $this->dietaryRequirements = $dietaryRequirements;
        return $this;
    }

    /**
     * @return string
     */
    public function getDietaryRequirements()
    {
        return $this->dietaryRequirements;
    }

    /**
     * @param string $platterOption
     *
     * @return WeekendAccommodation
     */
    public function setPlatterOption($platterOption)
    {
        $this->platterOption = $platterOption;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlatterOption()
    {
        return $this->platterOption;
    }

    /**
     * @param StepProgress $stepProgress
     * @return WeekendAccommodation
     */
    public static function fromStepProgress(StepProgress $stepProgress)
    {
        $instance = new self();
        foreach ($stepProgress->getFieldValues() as $fieldValue) {
            $instance->{$fieldValue->getFieldName()} = $fieldValue->getValue();
        }
        return $instance;
    }

    /**
     * @return array
     */
    public function toDataArray()
    {
        $data = [];
        $data['accommodation'] = $this->accommodation;
        $data['adaptedBedroomRequired'] = $this->adaptedBedroomRequired?1:0;
        $data['accommodationRequirements'] = $this->accommodationRequirements;
        $data['accommodationSharingWith'] = $this->accommodationSharingWith;
        $data['bedAndBreakfastAccommodation'] = $this->bedAndBreakfastAccommodation;
        $data['dietaryRequirements'] = $this->dietaryRequirements;
        $data['platterOption'] = $this->platterOption;
        $data['platter'] = $this->platter;
        return $data;
    }
}