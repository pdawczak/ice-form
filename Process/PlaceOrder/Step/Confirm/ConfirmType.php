<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\Confirm;

use Ice\FormBundle\Process\PlaceOrder\Step\AbstractType;

class ConfirmType extends AbstractType
{
    public function getTemplate(){
        return 'Confirm.html.twig';
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
        return parent::render($vars);
    }

    public function isComplete(){
        return false;
    }

    public function getReference(){
        return 'confirm';
    }
}
