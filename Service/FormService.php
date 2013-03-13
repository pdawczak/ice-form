<?php

namespace Ice\FormBundle\Service;

use Ice\FormBundle\Process\Registration;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader as TwigLoader;

class FormService{
    /** @var FormFactory */
    private $formFactory;

    /** @var TwigEngine */
    private $templating;

    /** @var TwigLoader */
    private $twigLoader;

    public function beginRegistrationProcess(){
        $registration = new Registration();
        $registration->setFormFactory($this->getFormFactory());
        $registration->setTemplating($this->getTemplating());
        return $registration;
    }

    /**
     * @param \Symfony\Component\Form\FormFactory $formFactory
     * @return FormService
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;
        return $this;
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    /**
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     * @return FormService
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
        return $this;
    }

    /**
     * @return \Symfony\Bundle\TwigBundle\TwigEngine
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @param \Symfony\Bundle\TwigBundle\Loader\FilesystemLoader $twigLoader
     * @return FormService
     */
    public function setTwigLoader($twigLoader)
    {
        $this->twigLoader = $twigLoader;
        $this->twigLoader->addPath(__DIR__.'/../Resources/views');
        return $this;
    }

    /**
     * @return \Symfony\Bundle\TwigBundle\Loader\FilesystemLoader
     */
    public function getTwigLoader()
    {
        return $this->twigLoader;
    }
}