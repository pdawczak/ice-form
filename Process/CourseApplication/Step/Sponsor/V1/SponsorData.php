<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Sponsor\V1;

class SponsorData
{
    private $sponsorPayment;
    private $sponsorName;
    private $sponsorAddress;
    private $sponsorTelephone;

    /**
     * @param mixed $sponsorPayment
     * @return SponsorData
     */
    public function setSponsorPayment($sponsorPayment)
    {
        $this->sponsorPayment = $sponsorPayment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSponsorPayment()
    {
        return $this->sponsorPayment;
    }

    /**
     * @param mixed $sponsorAddress
     * @return SponsorData
     */
    public function setSponsorAddress($sponsorAddress)
    {
        $this->sponsorAddress = $sponsorAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSponsorAddress()
    {
        return $this->sponsorAddress;
    }

    /**
     * @param mixed $sponsorName
     * @return SponsorData
     */
    public function setSponsorName($sponsorName)
    {
        $this->sponsorName = $sponsorName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSponsorName()
    {
        return $this->sponsorName;
    }

    /**
     * @param mixed $sponsorTelephone
     * @return SponsorData
     */
    public function setSponsorTelephone($sponsorTelephone)
    {
        $this->sponsorTelephone = $sponsorTelephone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSponsorTelephone()
    {
        return $this->sponsorTelephone;
    }
}
