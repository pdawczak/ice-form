<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

use Symfony\Component\Validator\Constraints as Assert;

class WeekendAccommodation{
    /**
     * @var string
     */
    private $accommodation;

    private $accommodationRequirementsGroup;

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

    public function setAccommodationRequirementsGroup($accommodationRequirementsGroup)
    {
        $this->accommodationRequirementsGroup = $accommodationRequirementsGroup;
        return $this;
    }

    public function getAccommodationRequirementsGroup()
    {
        return $this->accommodationRequirementsGroup;
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



}