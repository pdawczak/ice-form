<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

use Symfony\Component\Validator\Constraints as Assert;

class WeekendAccommodation{
    /**
     * @var string
     * @Assert\NotBlank(groups={"default"}, message="You must select an accommodation choice")
     */
    private $accommodationChoice;

    /**
     * @param string $accommodationChoice
     * @return WeekendAccommodation
     */
    public function setAccommodationChoice($accommodationChoice)
    {
        $this->accommodationChoice = $accommodationChoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccommodationChoice()
    {
        return $this->accommodationChoice;
    }
}