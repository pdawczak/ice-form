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

    /** @var string */
    private $receiptTemplate = 'MakePayment/Step/MakePayment.receipt.html.twig';

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


    public function getHtmlTemplate(){
        return 'MakePayment.html.twig';
    }

    public function getJavaScriptTemplate(){
        return 'MakePayment.js.twig';
    }

    public function getTitle(){
        return 'Make payment';
    }

    public function isAvailable(){
        return true;
    }

    public function renderHtml(array $vars = array())
    {
        $vars['order'] = $this->getParentProcess()->getOrder();
        $vars['transactionRequest'] = $this->request;
        $vars['isAdmin'] = $this->getParentProcess()->getAdministrator() !== null;

        $vars['iframeUrl'] = $this->getParentProcess()->getMercuryClient()->getPaymentPagesService()->getIframeUrl(
            $this->request,
            $this->getParentProcess()->getCustomer()
        );

        return parent::renderHtml($vars);
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

            if ($this->request->getTotalRequestAmount() === 0) {
                //Nothing to request, we're done.
                $this->getStepProgress()->setComplete();
            }

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

    /**
     * @param string $receiptTemplate
     * @return MakePaymentType
     */
    public function setReceiptTemplate($receiptTemplate)
    {
        $this->receiptTemplate = $receiptTemplate;
        return $this;
    }

    /**
     * @return string
     */
    public function getReceiptTemplate()
    {
        return $this->receiptTemplate;
    }

    public function renderReceipt() {
        $vars['form'] = $this->getForm()->createView();
        $vars['url'] = $this->getParentProcess()->getUrl();
        $vars['order'] = $this->getParentProcess()->getOrder();
        $vars['amount'] = $this->request->getTotalRequestAmount();
        return $this->getParentProcess()->getTemplating()->render($this->getReceiptTemplate(), $vars);
    }
}
