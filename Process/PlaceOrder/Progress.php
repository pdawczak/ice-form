<?php
namespace Ice\FormBundle\Process\PlaceOrder;

use Ice\FormBundle\Process\PlaceOrder;

use Ice\FormBundle\Process\PlaceOrder\Step\ChoosePlans\PlanChoice;
use Ice\MercuryClientBundle\Entity\Order;

class Progress{
    /**
     * @var StepProgress[]
     */
    private $stepProgresses = array();

    /**
     * @var PlanChoice[]
     */
    private $planChoices = array();

    /**
     * @var int
     */
    private $transactionRequestId = null;

    /**
     * @var Order
     */
    private $confirmedOrder;

    /**
     * @param string $reference
     * @param mixed $stepProgress
     * @return Progress
     */
    public function setStepProgress($reference, $stepProgress)
    {
        $this->stepProgresses[$reference] = $stepProgress;
        return $this;
    }

    /**
     * @return array|StepProgress[]
     */
    public function getStepProgresses()
    {
        return $this->stepProgresses;
    }

    /**
     * @param $reference
     * @return StepProgress
     */
    public function getStepProgress($reference)
    {
        if(!isset($this->stepProgresses[$reference])){
            $this->stepProgresses[$reference] = new StepProgress();
        }
        return $this->stepProgresses[$reference];
    }

    /**
     * @param PlanChoice[] $planChoices
     * @return $this
     */
    public function setPlanChoices($planChoices)
    {
        $this->planChoices = $planChoices;
        return $this;
    }

    /**
     * @return Step\ChoosePlans\PlanChoice[]
     */
    public function getPlanChoices()
    {
        return $this->planChoices;
    }

    /**
     * @param $bookingId
     * @return PlanChoice
     */
    public function getPlanChoiceByBookingId($bookingId){
        foreach($this->planChoices as $choice){
            if ($choice->getBookingId() === $bookingId) {
                return $choice;
            }
        }
        return null;
    }

    /**
     * @param \Ice\MercuryClientBundle\Entity\Order $confirmedOrder
     * @return Progress
     */
    public function setConfirmedOrder($confirmedOrder)
    {
        $this->confirmedOrder = $confirmedOrder;
        return $this;
    }

    /**
     * @return \Ice\MercuryClientBundle\Entity\Order
     */
    public function getConfirmedOrder()
    {
        return $this->confirmedOrder;
    }

    /**
     * @param int $transactionRequest
     * @return Progress
     */
    public function setTransactionRequestId($transactionRequestId)
    {
        $this->transactionRequestId = $transactionRequestId;
        return $this;
    }

    /**
     * @return int
     */
    public function getTransactionRequestId()
    {
        return $this->transactionRequestId;
    }
}