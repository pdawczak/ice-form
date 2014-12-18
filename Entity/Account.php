<?php

namespace Ice\FormBundle\Entity;

class Account
{
    /**
     * @var string
     */
    private $iceId;

    /**
     * @var string
     */
    private $lastNames;

    /**
     * @var
     */
    private $firstNames;

    /**
     * @var
     */
    private $title;

    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var \DateTime
     */
    private $dateOfBirth;

    /**
     * @param string $iceId
     * @return Account
     */
    public function setIceId($iceId)
    {
        $this->iceId = $iceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getIceId()
    {
        return $this->iceId;
    }

    /**
     * @param string $lastNames
     * @return Account
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
     * @param mixed $firstNames
     * @return Account
     */
    public function setFirstNames($firstNames)
    {
        $this->firstNames = $firstNames;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstNames()
    {
        return $this->firstNames;
    }

    /**
     * @param mixed $title
     * @return Account
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $emailAddress
     * @return Account
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
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param \DateTime $dob
     * @return $this
     */
    public function setDateOfBirth($dob)
    {
        $this->dateOfBirth = $dob;
        return $this;
    }
}
