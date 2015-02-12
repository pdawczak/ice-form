<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\ChoosePlans;

use Ice\PaymentPlan\PlanDefinition;

class PlanChoice{
    /** @var string */
    private $hash;

    /** @var int */
    private $bookingId;

    private function __construct(){}

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return int
     */
    public function getBookingId()
    {
        return $this->bookingId;
    }

    public static function getDefinitionHash(PlanDefinition $definition)
    {
        return md5(serialize($definition));
    }

    /**
     * @param $bookingId
     * @param $hash
     * @return PlanChoice
     */
    public static function withBookingIdAndHash($bookingId, $hash)
    {
        $instance = new self();
        $instance->bookingId = $bookingId;
        $instance->hash = $hash;
        return $instance;
    }
}
