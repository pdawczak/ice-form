<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\MakePayment;

use Ice\FormBundle\Process\PlaceOrder\Step\AbstractType;

class MakePaymentType extends AbstractType
{
    public function getTemplate(){
        return 'MakePayment.html.twig';
    }

    public function getTitle(){
        return 'Make payment';
    }

    public function isAvailable(){
        return true;
    }

    public function render(array $vars = array())
    {
        $vars['bookings'] = $this->getParentProcess()->getBookingsAvailableToOrder();
        return parent::render($vars);
    }

    public function isComplete(){
        return false;
    }

    public function getReference(){
        return 'makePayment';
    }
}
