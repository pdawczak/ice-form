<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\DietaryRequirements;

use Symfony\Component\Validator\Constraints as Assert;

class DietaryRequirements{
    /**
     * @var array
     */
    private $dietaryRequirementsListed = array();

    /**
     * @var string
     * @Assert\NotBlank(groups={"selected_other"})
     */
    private $dietaryRequirementsSpecific;

    /**
     * @param string $dietaryRequirementsSpecific
     * @return DietaryRequirements
     */
    public function setDietaryRequirementsSpecific($dietaryRequirementsSpecific)
    {
        $this->dietaryRequirementsSpecific = $dietaryRequirementsSpecific;
        return $this;
    }

    /**
     * @return string
     */
    public function getDietaryRequirementsSpecific()
    {
        return $this->dietaryRequirementsSpecific;
    }

    /**
     * @param array $dietaryRequirementsListed
     * @return DietaryRequirements
     */
    public function setDietaryRequirementsListed($dietaryRequirementsListed)
    {
        $this->dietaryRequirementsListed = $dietaryRequirementsListed;
        return $this;
    }

    /**
     * @return array
     */
    public function getDietaryRequirementsListed()
    {
        return $this->dietaryRequirementsListed;
    }
}