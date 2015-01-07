<?php

namespace Ice\FormBundle\Process\PlaceOrder\Step\Confirm;

use Ice\MercuryClientBundle\Entity\PaymentPlanInterface;
use Ice\MercuryClientBundle\Entity\Receivable;
use Ice\PaymentPlan\PaymentPlan;

class MercuryPaymentPlanAdapter implements PaymentPlanInterface
{
    /**
     * @var PaymentPlan
     */
    private $paymentPlan;

    /**
     * @var string
     */
    private $paymentMethod = Receivable::METHOD_ONLINE;

    //Construct using factory methods only
    private function __construct() {}

    /**
     * @param PaymentPlan $paymentPlan
     * @return MercuryPaymentPlanAdapter
     */
    public static function withPaymentPlan(PaymentPlan $paymentPlan)
    {
        $instance = new self();
        $instance->paymentPlan = $paymentPlan;
        return $instance;
    }

    /**
     * @param \DateTime $courseStartDate Start date of course
     * @param int $total Total amount owed in pence
     *
     * @return Receivable[]
     */
    public function getReceivables(\DateTime $courseStartDate, $total)
    {
        $receivables = [];
        foreach ($this->paymentPlan->getPlannedPayments() as $plannedPayment) {
            if (!$plannedPayment->hasDueDate()) {
                $dueDate = null;
            } else {
                $dueDate = new \DateTime($plannedPayment->getDueDate()->format('Y-m-d'));
            }
            $receivables[] = (new Receivable())
                ->setDueDate($dueDate)
                ->setAmount($plannedPayment->getAmount()->getAmount())
                ->setMethod($this->paymentMethod)
            ;
        }
        return $receivables;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->paymentPlan->getShortDescription();
    }

    /**
     * @return string
     */
    public function getLongDescription()
    {
        return $this->paymentPlan->getLongDescription();
    }

    /**
     * @param string $method Payment method constant
     *
     * @return PaymentPlanInterface
     */
    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
        return $this;
    }
}
