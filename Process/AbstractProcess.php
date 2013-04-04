<?php

namespace Ice\FormBundle\Process;

use Symfony\Component\Form\FormFactory;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Request;
use Ice\VeritasClientBundle\Service\VeritasClient,
    Ice\MinervaClientBundle\Service\MinervaClient,
    Ice\JanusClientBundle\Service\JanusClient;

abstract class AbstractProcess{

    /** @var FormFactory */
    private $formFactory;

    /** @var TwigEngine */
    private $templating;

    /** @var VeritasClient */
    private $veritasClient;

    /** @var MinervaClient */
    private $minervaClient;

    /** @var JanusClient */
    private $janusClient;

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

    /**
     * @param \Ice\JanusClientBundle\Service\JanusClient $janusClient
     * @return AbstractProcess
     */
    public function setJanusClient($janusClient)
    {
        $this->janusClient = $janusClient;
        return $this;
    }

    /**
     * @return \Ice\JanusClientBundle\Service\JanusClient
     */
    public function getJanusClient()
    {
        return $this->janusClient;
    }

    /**
     * @param \Ice\MinervaClientBundle\Service\MinervaClient $minervaClient
     * @return AbstractProcess
     */
    public function setMinervaClient($minervaClient)
    {
        $this->minervaClient = $minervaClient;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Service\MinervaClient
     */
    public function getMinervaClient()
    {
        return $this->minervaClient;
    }
}