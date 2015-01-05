<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Bursary\V2;

class BursaryData
{
    private $bursaryWishToApply;
    private $ukSchoolTeaching;
    private $previousUniStudy;
    private $irhStatement;
    private $schoolContact;

    /**
     * @param mixed $bursaryWishToApply
     * @return BursaryData
     */
    public function setBursaryWishToApply($bursaryWishToApply)
    {
        $this->bursaryWishToApply = $bursaryWishToApply;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBursaryWishToApply()
    {
        return $this->bursaryWishToApply;
    }

    /**
     * @param mixed $irhStatement
     * @return BursaryData
     */
    public function setIrhStatement($irhStatement)
    {
        $this->irhStatement = $irhStatement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIrhStatement()
    {
        return $this->irhStatement;
    }

    /**
     * @param mixed $previousUniStudy
     * @return BursaryData
     */
    public function setPreviousUniStudy($previousUniStudy)
    {
        $this->previousUniStudy = $previousUniStudy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreviousUniStudy()
    {
        return $this->previousUniStudy;
    }

    /**
     * @param mixed $ukSchoolTeaching
     * @return BursaryData
     */
    public function setUkSchoolTeaching($ukSchoolTeaching)
    {
        $this->ukSchoolTeaching = $ukSchoolTeaching;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUkSchoolTeaching()
    {
        return $this->ukSchoolTeaching;
    }

    /**
     * @param mixed $schoolContact
     * @return BursaryData
     */
    public function setSchoolContact($schoolContact)
    {
        $this->schoolContact = $schoolContact;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSchoolContact()
    {
        return $this->schoolContact;
    }
}
