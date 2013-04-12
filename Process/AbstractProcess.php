<?php

namespace Ice\FormBundle\Process;

use Symfony\Component\Form\FormFactory;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;
use Ice\VeritasClientBundle\Service\VeritasClient,
    Ice\MinervaClientBundle\Service\MinervaClient,
    Ice\JanusClientBundle\Service\JanusClient,
    Ice\MercuryClientBundle\Service\MercuryClient;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class AbstractProcess{

    /** @var FormFactory */
    private $formFactory;

    /** @var TwigEngine */
    private $templating;

    /** @var VeritasClient */
    private $veritasClient;

    /** @var MercuryClient */
    private $mercuryClient;

    /** @var MinervaClient */
    private $minervaClient;

    /** @var JanusClient */
    private $janusClient;

    /** @var string */
    private $url;

    /** @var Container */
    private $container;

    /** @var string */
    private $administratorUsername;

    /** @var Session */
    private $session;

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
     * @param \Ice\MercuryClientBundle\Service\MercuryClient $mercuryClient
     * @return AbstractProcess
     */
    public function setMercuryClient($mercuryClient)
    {
        $this->mercuryClient = $mercuryClient;
        return $this;
    }

    /**
     * @return \Ice\MercuryClientBundle\Service\MercuryClient
     */
    public function getMercuryClient()
    {
        return $this->mercuryClient;
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

    /**
     * @param string $url
     * @return AbstractProcess
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the administrator's username, or null to indicate that this process is not being completed by an
     * administrator
     *
     * @param $username
     * @return AbstractProcess
     */
    public function setAdministrator($username){
        $this->administratorUsername = $username;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAdministrator(){
        return $this->administratorUsername;
    }

    /**
     * @return bool
     */
    public function isAdministrator(){
        return null !== $this->administratorUsername;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession()
    {
        if(null == $this->session){
            $session = new Session();
            $this->session = $session;
        }
        return $this->session;
    }
}