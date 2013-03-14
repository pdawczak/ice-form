<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step;

use Ice\FormBundle\Process\CourseRegistration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

abstract class AbstractRegistrationStep extends AbstractType{
    protected $title;

    /** @var \Ice\FormBundle\Process\CourseRegistration */
    private $parentProcess;

    /**
     * @param CourseRegistration $parentProcess
     */
    public function __construct(CourseRegistration $parentProcess){
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
     * @param \Ice\FormBundle\Process\CourseRegistration $parentProcess
     * @return AbstractRegistrationStep
     */
    public function setParentProcess($parentProcess)
    {
        $this->parentProcess = $parentProcess;
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Process\CourseRegistration
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