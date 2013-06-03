<?php

namespace Ice\FormBundle\Process\MakePayment\Step;

use Symfony\Component\Form\AbstractType as BaseAbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\RuntimeException;
use Ice\FormBundle\Process\MakePayment;

abstract class AbstractType extends BaseAbstractType{
    /** @var string */
    protected $title;

    /** @var Form */
    private $form;

    /** @var object */
    private $entity;

    /** @var \Ice\FormBundle\Process\MakePayment */
    private $parentProcess;

    /** @var bool */
    private $prepared = false;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('stepReference', 'hidden', array(
            'data'=>$this->getReference(),
            'mapped'=>false
        ))
        ->add('progressId', 'hidden', array(
            'data'=>$this->getParentProcess()->getProgressId(),
            'mapped'=>false
        ));
    }

    /**
     * @param MakePayment $parentProcess
     * @param string|null $reference
     */
    public function __construct(MakePayment $parentProcess, $reference = null){
        $this->parentProcess = $parentProcess;
    }

    /**
     * @return string
     */
    public function getName(){
        return '';
    }

    /**
     * @return string
     */
    abstract public function getTemplate();

    /**
     * @param array $vars
     * @return mixed
     */
    public function render(array $vars = array()){
        $vars['form'] = $this->getForm()->createView();
        $vars['url'] = $this->getParentProcess()->getUrl();
        return $this->getParentProcess()->getTemplating()->render('MakePayment/Step/'.$this->getTemplate(), $vars);
    }

    /**
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    protected function getForm(){
        if(null === $this->form){
            $this->form = $this->getParentProcess()->getFormFactory()->create($this, $this->getEntity());
        }
        return $this->form;
    }

    /**
     * @param \Ice\FormBundle\Process\MakePayment $parentProcess
     * @return AbstractType
     */
    public function setParentProcess($parentProcess)
    {
        $this->parentProcess = $parentProcess;
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Process\MakePayment
     */
    public function getParentProcess()
    {
        return $this->parentProcess;
    }

    /**
     * @param object $entity
     * @return AbstractType
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param boolean $prepared
     * @return AbstractType
     */
    public function setPrepared($prepared = true)
    {
        $this->prepared = $prepared;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrepared()
    {
        return $this->prepared;
    }

    /**
     * @return mixed
     */
    public function prepare()
    {
        $this->setPrepared();
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        foreach($this->getParentProcess()->getSteps() as $index=>$step) {
            if($step->getReference() === $this->getReference()) {
                return $index;
            }
        }
    }

    /**
     * @return string
     */
    abstract public function getReference();

    /**
     * @param Request $request
     * @return mixed
     */
    abstract public function processRequest(Request $request);

    /**
     * @return MakePayment\StepProgress
     */
    public function getStepProgress(){
        return $this->getParentProcess()->getProgress()->getStepProgress($this->getReference());
    }

    /**
     * @return bool
     */
    abstract public function isComplete();

    /**
     * @return Response
     */
    public function getAjaxResponse()
    {
        return new Response('No AJAX response is available for this step');
    }
}