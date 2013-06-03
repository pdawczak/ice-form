<?php

namespace Ice\FormBundle\Service;

use Ice\FormBundle\Process\CourseRegistration;
use Ice\FormBundle\Process\PlaceOrder;
use Ice\FormBundle\Process\MakePayment;

use Ice\MercuryClientBundle\Entity\Order;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader as TwigLoader;
use Ice\VeritasClientBundle\Service\VeritasClient;
use Ice\MinervaClientBundle\Service\MinervaClient;
use Ice\JanusClientBundle\Service\JanusClient;
use Ice\MercuryClientBundle\Service\MercuryClient;
use Symfony\Component\DependencyInjection\Container;

class FormService{
    /** @var FormFactory */
    private $formFactory;

    /** @var TwigEngine */
    private $templating;

    /** @var TwigLoader */
    private $twigLoader;

    /** @var MercuryClient */
    private $mercuryClient;

    /** @var MinervaClient */
    private $minervaClient;

    /** @var VeritasClient */
    private $veritasClient;

    /** @var JanusClient */
    private $janusClient;

    /** @var Container */
    private $container;

    /**
     * Begin OR resume a course registration
     *
     * @param int $courseId
     * @param string|null $registrantId
     * @return CourseRegistration
     */
    public function beginCourseRegistrationProcess($courseId, $registrantId = null){
        $courseRegistration = new CourseRegistration();
        $courseRegistration
            ->setCourseId($courseId)
            ->setFormFactory($this->getFormFactory())
            ->setTemplating($this->getTemplating())
            ->setVeritasClient($this->getVeritasClient())
            ->setJanusClient($this->getJanusClient())
            ->setMinervaClient($this->getMinervaClient())
        ;

        if($registrantId){
            $courseRegistration->setRegistrantId($registrantId);
        }

        return $courseRegistration;
    }

    /**
     * @param string $customerId
     * @return PlaceOrder
     */
    public function placeOrder($customerId){
        $placeOrder = new PlaceOrder();
        $placeOrder
            ->setCustomerId($customerId)
            ->setFormFactory($this->getFormFactory())
            ->setTemplating($this->getTemplating())
            ->setVeritasClient($this->getVeritasClient())
            ->setJanusClient($this->getJanusClient())
            ->setMinervaClient($this->getMinervaClient())
            ->setMercuryClient($this->getMercuryClient())
            ->setPaymentPlanService($this->getContainer()->get('mercury.payment_plans'))
        ;
        return $placeOrder;
    }

    /**
     * @param \Ice\MercuryClientBundle\Entity\Order $order
     * @return MakePayment
     */
    public function makePayment(Order $order){
        $makePayment = new MakePayment();
        $makePayment
            ->setOrder($order)
            ->setFormFactory($this->getFormFactory())
            ->setTemplating($this->getTemplating())
            ->setVeritasClient($this->getVeritasClient())
            ->setJanusClient($this->getJanusClient())
            ->setMinervaClient($this->getMinervaClient())
            ->setMercuryClient($this->getMercuryClient())
        ;
        return $makePayment;
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

    /**
     * @param \Ice\VeritasClientBundle\Service\VeritasClient $veritasClient
     * @return FormService
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
     * @param \Ice\MinervaClientBundle\Service\MinervaClient $minervaClient
     * @return FormService
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
     * @param \Ice\JanusClientBundle\Service\JanusClient $janusClient
     * @return FormService
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
     * @param \Symfony\Component\DependencyInjection\Container $container
     * @return AbstractProcess
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param \Ice\MercuryClientBundle\Service\MercuryClient $mercuryClient
     * @return FormService
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
}