<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\DietaryRequirements;

use Ice\JanusClientBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Ice\MinervaClientBundle\Entity\StepProgress;

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
     * @param User              $user
     * @param StepProgress|null $step
     *
     * @return DietaryRequirements
     */
    public static function fromUserAndStepProgress(User $user, $step)
    {
        $instance = new static();
        $instance->setDietaryRequirementsListed($user->getAttributeValueByName('dietaryRequirementsListed', null))
            ->setDietaryRequirementsSpecific($user->getAttributeValueByName('dietaryRequirementsSpecific', null));


        if(null !== $step) {
            // Add or overwrite based on set StepProgress FieldValues.
            //
            // The values are keyed the same as the property values
            //
            // I think there's a possibility that an end-user could add HTML inputs into the page to set values
            // that we don't want them to, but tests haven't been able to confirm this is the case.
            foreach($step->getFieldValues() as $field) {
                if (property_exists($instance, $field->getFieldName())) {
                    $name = $field->getFieldName();
                    $instance->$name = $field->getValue();
                }
            }
        }

        return $instance;
    }

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
