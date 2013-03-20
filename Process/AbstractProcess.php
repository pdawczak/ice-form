<?php

namespace Ice\FormBundle\Process;

use Symfony\Component\Form\FormFactory;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Request;
use Ice\VeritasClientBundle\Service\VeritasClient;

abstract class AbstractProcess{
    /** @var string */
    private $iceId;

    /** @var FormFactory */
    private $formFactory;

    /** @var TwigEngine */
    private $templating;

    /** @var VeritasClient */
    private $veritasClient;

    /**
     * @param $iceId
     * @return AbstractProcess
     */
    public final function setIceId($iceId)
    {
        $this->iceId = $iceId;
        return $this;
    }

    /**
     * @return string
     */
    public final function getIceId()
    {
        return $this->iceId;
    }

    /**
     * @param \Symfony\Component\Form\FormFactory $formFactory
     * @return AbstractProcess
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
     * @return AbstractProcess
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

    abstract public function processRequest(Request $request);

    abstract public function isComplete();

    /**
     * @param \Ice\VeritasClientBundle\Service\VeritasClient $veritasClient
     * @return CourseRegistration
     */
    public function setVeritasClient($veritasClient)
    {
        $this->veritasClient = $veritasClient;
        return $this;
    }

    /**
     * @return \Ice\VeritasClientBundle\Service\VeritasClient
     */
    public function getVeritasClient()
    {
        return $this->veritasClient;
    }
}