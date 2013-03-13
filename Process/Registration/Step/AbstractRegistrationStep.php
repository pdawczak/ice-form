<?php

namespace Ice\FormBundle\Process\Registration\Step;

use Ice\FormBundle\Process\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

abstract class AbstractRegistrationStep extends AbstractType{
    protected $title;

    /** @var \Ice\FormBundle\Process\Registration */
    private $parentProcess;

    /**
     * @param Registration $parentProcess
     */
    public function __construct(Registration $parentProcess){
        $this->parentProcess = $parentProcess;
    }

    public function getTitle(){
        $className = end(explode('\\', get_class($this)));
        return ucfirst(strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $className)));
    }

    public function render(){
        $form = $this->getParentProcess()->getFormFactory()->create($this)->createView();
        return $this->getParentProcess()->getTemplating()->render('Registration/Step/'.$this->getTemplate(), array(
            'form' => $form
        ));
    }

    /**
     * @return string
     */
    public function getTemplate(){
        return 'default.html.twig';
    }

    /**
     * @param \Ice\FormBundle\Process\Registration $parentProcess
     * @return AbstractRegistrationStep
     */
    public function setParentProcess($parentProcess)
    {
        $this->parentProcess = $parentProcess;
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Process\Registration
     */
    public function getParentProcess()
    {
        return $this->parentProcess;
    }

    public function processSubmission(){}

    public function getName(){
        return '';
    }

    //abstract public function getForm();
}