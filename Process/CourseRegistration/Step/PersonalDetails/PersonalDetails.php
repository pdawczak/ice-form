<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\PersonalDetails;

use Ice\JanusClientBundle\Entity\User;

class PersonalDetails{
    /** @var string */
    private $email;

    /** @var string */
    private $title;

    /** @var string */
    private $firstNames;

    /** @var string */
    private $lastNames;

    /** @var string */
    private $middleNames;

    /** @var string */
    private $gender;

    /** @var \DateTime */
    private $dob;

    /** @var string */
    private $plainPassword;

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
     * @param User $user
     * @return PersonalDetails
     */
    public static function fromUser(User $user){
        $instance = new self();
        $instance->setTitle($user->getTitle());
        $instance->setFirstNames($user->getFirstNames());
        $instance->setMiddleNames($user->getMiddleNames());
        $instance->setLastNames($user->getLastNames());
        $instance->setEmail($user->getEmail());
        $instance->setDob($user->getDob());
        return $instance;
    }
}