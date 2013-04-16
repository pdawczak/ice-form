<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\Confirm;

use Ice\FormBundle\Process\PlaceOrder\Step\AbstractType;
use Ice\MercuryClientBundle\Entity\Order;

class ConfirmType extends AbstractType
{
    /** @var Order */
    private $order;

    public function getTemplate(){
        if(count($this->order->getSuborders())>0) {
            return 'Confirm.html.twig';
        }
        else{
            return 'OrderEmpty.html.twig';
        }
    }

    public function getTitle(){
        return 'Confirm order';
    }

    public function isAvailable(){
        return true;
    }

    public function render(array $vars = array())
    {
        $vars['bookings'] = $this->getParentProcess()->getBookingsAvailableToOrder();
        $vars['order'] = $this->order;
        return parent::render($vars);
    }

    public function isComplete(){
        return $this->getStepProgress()->isComplete();
    }

    public function processRequest($request){
        $newOrder = $this->getParentProcess()->getMercuryClient()->createOrder($this->order);
        $this->getParentProcess()->getProgress()->setConfirmedOrder($newOrder);
        $this->getStepProgress()->setComplete();
        $this->getParentProcess()->saveProgress();
    }

    public function prepare()
    {
        $builder = $this->getParentProcess()->getMercuryClient()->getNewOrderBuilder();
        $builder->setCustomerByUser($this->getParentProcess()->getCustomer());
        $progress = $this->getParentProcess()->getProgress();
        foreach($this->getParentProcess()->getBookingsAvailableToOrder() as $booking){
            if($planChoice = $progress->getPlanChoiceByBookingId($booking->getId())){
                $course = $this->getParentProcess()->getVeritasClient()
                    ->getCourse($booking->getAcademicInformation()->getCourseId());
                $paymentPlan = $this->getParentProcess()->getPaymentPlanService()->getPaymentPlan(
                    $planChoice->getCode(),
                    $planChoice->getVersion()
                );
                $builder->addNewBooking($booking, $paymentPlan, $course);
            }
        }
        $this->order = $builder->getOrder();
        $this->setPrepared();
    }

    public function getReference(){
        return 'confirm';
    }
}
