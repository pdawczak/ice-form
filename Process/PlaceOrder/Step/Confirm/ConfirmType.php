<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\Confirm;

use Ice\FormBundle\Infrastructure\Minerva\MinervaClientBookingAdapter;
use Ice\FormBundle\Process\PlaceOrder\Step\AbstractType;
use Ice\MercuryClientBundle\Entity\Order;
use Ice\MercuryClientBundle\Entity\Receivable;
use Ice\MercuryClientBundle\Exception\CapacityException as OrderBuilderCapacityException;
use Ice\FormBundle\Exception\CapacityException;
use Ice\PaymentPlan\PaymentPlan;
use Symfony\Component\HttpFoundation\Request;
use Ice\FormBundle\Process\PlaceOrder\Step\ChoosePlans\PlanChoice;

class ConfirmType extends AbstractType
{
    /** @var Order */
    private $order;

    public function getTemplate()
    {
        if (count($this->order->getSuborders()) > 0) {
            return 'Confirm.html.twig';
        } else {
            return 'OrderEmpty.html.twig';
        }
    }

    public function getTitle()
    {
        return 'Confirm order';
    }

    public function isAvailable()
    {
        return true;
    }

    public function render(array $vars = array())
    {
        $vars['bookings'] = $this->getParentProcess()->getBookingsAvailableToOrder();
        $vars['order'] = $this->order;
        return parent::render($vars);
    }

    public function isComplete()
    {
        return $this->getStepProgress()->isComplete();
    }

    public function processRequest(Request $request)
    {
        if ($this->getParentProcess()->getProgress()->getConfirmedOrder()) {
            return;
        }
        $newOrder = $this->getParentProcess()->getMercuryClient()->createOrder($this->order);
        $this->getParentProcess()->getProgress()->setConfirmedOrder($newOrder);
        $this->getStepProgress()->setComplete();
        $this->getParentProcess()->saveProgress();
    }

    public function prepare()
    {
        if ($this->getParentProcess()->getProgress()->getConfirmedOrder()) {
            $this->setPrepared();
            return;
        }
        $builder = $this->getParentProcess()->getMercuryClient()->getNewOrderBuilder();

        $builder->setCustomer($this->getParentProcess()->getCustomer());

        $calculator = $this->getParentProcess()->getPlanFactory();


        $progress = $this->getParentProcess()->getProgress();

        foreach ($this->getParentProcess()->getBookingsAvailableToOrder() as $booking) {
            if ($planChoice = $progress->getPlanChoiceByBookingId($booking->getId())) {
                $course = $booking->getAcademicInformation()->getCourse();

                foreach ($course->getAvailablePaymentPlans() as $availablePlan) {
                    if (PlanChoice::getDefinitionHash($availablePlan->getDefinition()) === $planChoice->getHash()) {
                        $chosenPlan = $availablePlan;
                    }
                }

                if (!isset($chosenPlan)) {
                    throw new \Exception("A payment plan has been selected which is not available");
                }

                /** @var PaymentPlan $paymentPlan */
                $paymentPlan = $calculator->calculatePlan(
                    $chosenPlan->getDefinition(),
                    (new MinervaClientBookingAdapter())->getBooking($booking)
                );

                $mercuryPaymentPlan = MercuryPaymentPlanAdapter::withPaymentPlan($paymentPlan);

                try {
                    $builder->addNewBooking($booking, $mercuryPaymentPlan, $course);
                } catch (OrderBuilderCapacityException $mercuryCapacityException) {
                    $e = (new CapacityException('Capacity problems'))
                        ->setBookingItem($mercuryCapacityException->getBookingItem())
                        ->setCourseItem($mercuryCapacityException->getCourseItem())
                        ->setBooking($booking)
                        ->setCourse($course);

                    throw $e;
                }
            }
        }
        $this->order = $builder->getOrder();
        $this->setPrepared();
    }

    public function getReference()
    {
        return 'confirm';
    }
}
