<?php
namespace Ice\FormBundle\Process\MakePayment\Step\MakePayment;

use Ice\FormBundle\Process\MakePayment\Step\AbstractType;
use Ice\MercuryClientBundle\Entity\TransactionRequest;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
        $builder->addEventListener(FormEvents::PRE_BIND, function(FormEvent $e) {
            $data = $e->getData();
            if (isset($data['viewOrder'])) {
                $this->getParentProcess()->setIsViewOrderClicked(true);
                unset($data['viewOrder']);
            }
            if (isset($data['studentHome'])) {
                $this->getParentProcess()->setIsStudentHomeClicked(true);
                unset($data['studentHome']);
            }
            $e->setData($data);
        });
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
        $vars['order'] = $this->getParentProcess()->getOrder();
        $vars['transactionRequest'] = $this->request;

        $vars['iframeUrl'] = $this->getParentProcess()->getMercuryClient()->getPaymentPagesService()->getIframeUrl(
            $this->request,
            $this->getParentProcess()->getCustomer()
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
                    $this->getParentProcess()->getOrder()
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

        if (
            $this->getParentProcess()->isViewOrderClicked() ||
            $this->getParentProcess()->isStudentHomeClicked()
        ) {
            $this->getStepProgress()->setComplete(true);
            $this->getParentProcess()->saveProgress();
            return;
        }

        if($this->request->getTransaction()) {
            $transactionStatus = 'SUCCESS';
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
        return $this->getParentProcess()->getTemplating()->render('MakePayment/Step/PaymentReceived.form.html.twig', $vars);
    }

    public function renderReceipt() {
        $vars['form'] = $this->getForm()->createView();
        $vars['url'] = $this->getParentProcess()->getUrl();
        $vars['order'] = $this->getParentProcess()->getOrder();
        $vars['amount'] = $this->request->getTotalRequestAmount();
        return $this->getParentProcess()->getTemplating()->render('MakePayment/Step/MakePayment.receipt.html.twig', $vars);
    }
}
