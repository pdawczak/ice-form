<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\PersonalDetails;

use Ice\JanusClientBundle\Entity\User;

use Symfony\Component\Validator\Constraints as Assert;

class PersonalDetails{
    /**
     * @var string
     * @Assert\NotBlank(groups={"new_user"}, message="Please provide an email address")
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(groups={"new_user"}, message="Please provide a title")
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(groups={"new_user"}, message="Please provide a first name")
     */
    private $firstNames;

    /**
     * @var string
     * @Assert\NotBlank(groups={"new_user"}, message="Please provide a last name")
     */
    private $lastNames;

    /** @var string */
    private $middleNames;

    /** @var string */
    private $gender;

    /** @var \DateTime */
    private $dob;

    /**
     * @var string
     * @Assert\NotBlank(groups={"new_user"}, message="Please provide a password")
     */
    private $plainPassword;

    /**
     * @var string
     */
    private $registrantId;

    /**
     * @param string $email
     * @return PersonalDetails
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param \DateTime $dob
     * @return PersonalDetails
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param string $firstNames
     * @return PersonalDetails
     */
    public function setFirstNames($firstNames)
    {
        $this->firstNames = $firstNames;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstNames()
    {
        return $this->firstNames;
    }

    /**
     * @param string $lastNames
     * @return PersonalDetails
     */
    public function setLastNames($lastNames)
    {
        $this->lastNames = $lastNames;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastNames()
    {
        return $this->lastNames;
    }

    /**
     * @param string $middleNames
     * @return PersonalDetails
     */
    public function setMiddleNames($middleNames)
    {
        $this->middleNames = $middleNames;
        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleNames()
    {
        return $this->middleNames;
    }

    /**
     * @param string $plainPassword
     * @return PersonalDetails
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $title
     * @return PersonalDetails
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $gender
     * @return PersonalDetails
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getFullName(){
        return $this->getLastNames().', '.implode(' ',
            array_filter(array($this->getTitle(), $this->getFirstNames(), $this->getMiddleNames())));
    }

    /**
     * @param string $registrantId
     * @return PersonalDetails
     */
    public function setRegistrantId($registrantId)
    {
        $this->registrantId = $registrantId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegistrantId()
    {
        return $this->registrantId;
    }

    /**
     * @param User $user
     * @return PersonalDetails
     */
    public static function fromUser(User $user){
        $instance = new self();
        $instance->setRegistrantId($user->getUsername());
        $instance->setTitle($user->getTitle());
        $instance->setFirstNames($user->getFirstNames());
        $instance->setMiddleNames($user->getMiddleNames());
        $instance->setLastNames($user->getLastNames());
        $instance->setEmail($user->getEmail());
        $instance->setDob($user->getDob());
        return $instance;
    }
}