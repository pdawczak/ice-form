<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\Confirm;

use Ice\FormBundle\Infrastructure\Minerva\MinervaClientBookingAdapter;
use Ice\FormBundle\Process\PlaceOrder\Step\AbstractType;
use Ice\MercuryClientBundle\Adapter\Builder\Input\JanusClientUserAdapter;
use Ice\MercuryClientBundle\Adapter\Builder\Input\VeritasClientCourseAdapter;
use Ice\MercuryClientBundle\Adapter\Builder\Input\VeritasClientCourseAndMinervaBookingAdapter;
use Ice\MercuryClientBundle\Adapter\Builder\Input\VeritasClientCourseAndMinervaClientBookingAdapter;
use Ice\MercuryClientBundle\Builder\Input\OrderableBookingInterface;
use Ice\MercuryClientBundle\Builder\OrderBuilder;
use Ice\MercuryClientBundle\Entity\Order;
use Ice\MercuryClientBundle\Entity\Receivable;
use Ice\FormBundle\Exception\CapacityException;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\PaymentPlan\PaymentPlan;
use Ice\VeritasClientBundle\Entity\Course;
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

                $proposedSuborderFactory = $this->getParentProcess()->getProposedSuborderFactory();

                $orderableBooking = VeritasClientCourseAndMinervaClientBookingAdapter::adaptCourseAndBooking($booking, $course);

                $this->throwExceptionIfStockIssue($orderableBooking, $booking, $course);

                $builder->addProposedSuborder(
                    $proposedSuborderFactory->getProposedSuborder(
                        $orderableBooking,
                        null,
                        VeritasClientCourseAdapter::adaptCourse($course),
                        JanusClientUserAdapter::adaptUser($this->getParentProcess()->getUser()),
                        $paymentPlan,
                        Receivable::METHOD_ONLINE
                    )
                );
            }
        }
        $this->order = $builder->getOrder();
        $this->setPrepared();
    }

    private function throwExceptionIfStockIssue(OrderableBookingInterface $orderableBooking, Booking $booking, Course $course)
    {
        foreach ($orderableBooking->getOrderableItems() as $orderableItem) {
            if (!$orderableItem->isInStock()) {
                $e = (new CapacityException('Capacity problems'))
                    ->setBooking($booking)
                    ->setCourse($course);

                throw $e;
            }
        }
    }

    public function getReference()
    {
        return 'confirm';
    }
}
