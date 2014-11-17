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
