<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\PersonalDetails;

use Ice\MinervaClientBundle\Entity\StepProgress;
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
    private $sex;

    /** @var \DateTime */
    private $dob;

    /**
     * @var string 'Y' or 'N'
     */
    private $previousContact;

    /**
     * @var string
     */
    private $previousTitle;

    /**
     * @var string
     */
    private $previousFirstName;

    /**
     * @var string
     */
    private $previousMiddleName;

    /**
     * @var string
     */
    private $previousLastName;

    /**
     * @var string
     */
    private $crsId;

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
     * @param string $sex
     * @return PersonalDetails
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
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
     * @param User         $user
     * @param StepProgress $step
     *
     * @return PersonalDetails
     */
    public static function fromUserAndStepProgress(User $user, StepProgress $step){
        // Set based on User first
        $instance = new self();
        $instance
            ->setRegistrantId($user->getUsername())
            ->setTitle($user->getTitle())
            ->setFirstNames($user->getFirstNames())
            ->setMiddleNames($user->getMiddleNames())
            ->setLastNames($user->getLastNames())
            ->setEmail($user->getEmail())
            ->setDob($user->getDob())
        ;

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

        return $instance;
    }

    /**
     * @param string $previousContact
     *
     * @return PersonalDetails
     */
    public function setPreviousContact($previousContact)
    {
        $this->previousContact = $previousContact;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreviousContact()
    {
        return $this->previousContact;
    }

    /**
     * @param string $crsId
     *
     * @return PersonalDetails
     */
    public function setCrsId($crsId)
    {
        $this->crsId = $crsId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCrsId()
    {
        return $this->crsId;
    }

    /**
     * @param string $previousFirstName
     *
     * @return PersonalDetails
     */
    public function setPreviousFirstName($previousFirstName)
    {
        $this->previousFirstName = $previousFirstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreviousFirstName()
    {
        return $this->previousFirstName;
    }

    /**
     * @param string $previousLastName
     *
     * @return PersonalDetails
     */
    public function setPreviousLastName($previousLastName)
    {
        $this->previousLastName = $previousLastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreviousLastName()
    {
        return $this->previousLastName;
    }

    /**
     * @param string $previousMiddleName
     *
     * @return PersonalDetails
     */
    public function setPreviousMiddleName($previousMiddleName)
    {
        $this->previousMiddleName = $previousMiddleName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreviousMiddleName()
    {
        return $this->previousMiddleName;
    }

    /**
     * @param string $previousTitle
     *
     * @return PersonalDetails
     */
    public function setPreviousTitle($previousTitle)
    {
        $this->previousTitle = $previousTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreviousTitle()
    {
        return $this->previousTitle;
    }

}