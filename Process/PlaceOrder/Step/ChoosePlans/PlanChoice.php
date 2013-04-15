<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\ChoosePlans;

class PlanChoice{
    /** @var string */
    private $code;

    /** @var string */
    private $version;

    /** @var int */
    private $bookingId;

    /**
     * @param string $code
     * @return PlanChoice
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $version
     * @return PlanChoice
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $bookingId
     * @return PlanChoice
     */
    public function setBookingId($bookingId)
    {
        $this->bookingId = $bookingId;
        return $this;
    }

    /**
     * @return int
     */
    public function getBookingId()
    {
        return $this->bookingId;
    }
}