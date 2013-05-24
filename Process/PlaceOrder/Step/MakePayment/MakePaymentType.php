<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\MakePayment;

use Ice\FormBundle\Process\PlaceOrder\Step\AbstractType;
use Ice\MercuryClientBundle\Entity\TransactionRequest;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MakePaymentType extends AbstractType
{
    /**
     * @var TransactionRequest
     */
    private $request;

    private $ajaxResponse;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }


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
        $vars['iframeUrl'] = $this->request->getIframeUrl(array(
                'billinglastname'=>'test'
            )
        );
        return parent::render($vars);
    }

    public function isComplete(){
        return $this->getStepProgress()->isComplete();
    }

    /**
     * @return bool
     */
    public function isPrepared()
    {
        return $this->getParentProcess()->getProgress()->getTransactionRequestId() && $this->request;
    }


    public function prepare(){
        if($this->isPrepared())
            return;

        if ($requestReference = $this->getParentProcess()->getProgress()->getTransactionRequestId()) {
            $this->request = $this->getParentProcess()->getMercuryClient()
                ->getTransactionRequestByReference(
                    $requestReference
                );
        }
        else {
            $this->request = $this->getParentProcess()->getMercuryClient()
                ->requestOutstandingOnlineTransactionsByOrder(
                    $this->getParentProcess()->getProgress()->getConfirmedOrder()
                );

            $this->getParentProcess()->getProgress()->setTransactionRequestId(
                $this->request->getReference()
            );

            $this->getParentProcess()->saveProgress();
        }
    }

    public function getReference(){
        return 'makePayment';
    }

    /**
     * @return Response
     */
    public function getAjaxResponse()
    {
        return $this->ajaxResponse;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function processRequest(Request $request)
    {
        $form = $this->getForm();
        $form->bind($request);

        if($this->request->getTransaction()) {
            $transactionStatus = 'SUCCESS';
            $this->getStepProgress()->setComplete();
            $this->getParentProcess()->saveProgress();
            $html = $this->renderReceipt();
        }
        else{
            $transactionStatus = 'PENDING';
            $html = '';
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json', true);
        $response->setContent(json_encode(
            [
                'transaction_status'=>$transactionStatus,
                'html'=>$html
            ]
        ));

        $this->getParentProcess()->setAjaxResponse($response);
    }

    public function renderForm(array $vars = array()){
        $vars['form'] = $this->getForm()->createView();
        $vars['url'] = $this->getParentProcess()->getUrl();
        return $this->getParentProcess()->getTemplating()->render('PlaceOrder/Step/PaymentReceived.form.html.twig', $vars);
    }

    public function renderReceipt() {
        $vars['form'] = $this->getForm()->createView();
        $vars['url'] = $this->getParentProcess()->getUrl();
        return $this->getParentProcess()->getTemplating()->render('PlaceOrder/Step/MakePayment.receipt.html.twig', $vars);
    }
}
