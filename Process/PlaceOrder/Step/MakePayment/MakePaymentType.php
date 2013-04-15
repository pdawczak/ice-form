<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\MakePayment;

use Ice\FormBundle\Process\PlaceOrder\Step\AbstractType;
use Ice\MercuryClientBundle\Entity\TransactionRequest;

class MakePaymentType extends AbstractType
{
    /**
     * @var TransactionRequest
     */
    private $request;

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
        $vars['order'] = $this->getParentProcess()->getProgress()->getConfirmedOrder();
        $vars['transactionRequest'] = $this->request;
        $vars['iframeUrl'] = $this->request->getIframeUrl();
        return parent::render($vars);
    }

    public function isComplete(){
        return $this->getStepProgress()->isComplete();
    }

    public function prepare(){
        if($this->isPrepared())
            return;

        $this->request = $this->getParentProcess()->getMercuryClient()
            ->requestOutstandingOnlineTransactionsByOrder(
                $this->getParentProcess()->getProgress()->getConfirmedOrder()
            );

        $this->setPrepared();
    }

    public function getReference(){
        return 'makePayment';
    }
}
