<?php

namespace Ice\FormBundle\Process\PlaceOrder;

use Ice\FormBundle\Entity\Booking;
use Ice\PaymentPlan\Calculator\PaymentPlanCalculatorInterface;
use Ice\PaymentPlan\PlanDefinition;
use Ice\PaymentPlan\PlanParameters;
use Money\Money;

class CalculatedPlanFactory
{
    /**
     * @var PaymentPlanCalculatorInterface
     */
    private $calculator;

    public function __construct(PaymentPlanCalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param PlanDefinition $definition
     * @param Booking $booking
     * @return \Ice\PaymentPlan\PaymentPlan
     * @throws \Exception
     */
    public function calculatePlan(PlanDefinition $definition, Booking $booking)
    {
        $parametersArray = [];

        $totalBursaryDeduction = 0;
        $totalPrice = 0;

        foreach ($booking->getBookingItems() as $bookingItem)
        {
            if ($bookingItem->isBursaryOrDiscount()) {
                $totalBursaryDeduction -= $bookingItem->getPriceInPence();
            }
            $totalPrice += $bookingItem->getPriceInPence();
        }

        $parametersArray['bursary_total_deduction'] = $totalBursaryDeduction;

        $parameters = PlanParameters::fromArray($parametersArray);


        if (!$this->calculator->isAvailable($definition, Money::GBP($totalPrice), $parameters))
        {
            throw new \Exception(sprintf("Plan '%s' is not supported", $definition->getName()));
        }
        return $this->calculator->getPlan($definition, Money::GBP($totalPrice), $parameters);
    }
}
