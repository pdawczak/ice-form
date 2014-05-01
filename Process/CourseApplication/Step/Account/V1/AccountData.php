<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Account\V1;

use Ice\FormBundle\Entity\CourseApplicationFieldValue;
use Ice\FormBundle\Entity\CourseApplicationStep;
use Ice\FormBundle\Process\CourseApplication\Persistence\FieldValueSourceInterface;

class AccountData
{
    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $firstNames;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var
     */
    private $plainPassword;

    /**
     * @var string
     */
    private $dob;

    /**
     * @var string
     */
    private $sex;

    /**
     * @param string $lastName
     * @return AccountData
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $firstNames
     * @return AccountData
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
     * @param mixed $plainPassword
     * @return AccountData
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $title
     * @return AccountData
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
     * @param string $emailAddress
     * @return AccountData
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $dob
     * @return AccountData
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
        return $this;
    }

    /**
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param string $sex
     * @return AccountData
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
}
