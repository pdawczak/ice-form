<?php

namespace Ice\FormBundle\Service;

use Ice\FormBundle\Process\CourseRegistration;
use Minerva\NotFoundException;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader as TwigLoader;
use Ice\VeritasClientBundle\Service\VeritasClient;
use Ice\MinervaClientBundle\Service\MinervaClient;
use Ice\MinervaClientBundle\Service\JanusClient;

class FormService{
    /** @var FormFactory */
    private $formFactory;

    /** @var TwigEngine */
    private $templating;

    /** @var TwigLoader */
    private $twigLoader;

    /** @var MinervaClient */
    private $minervaClient;

    /** @var VeritasClient */
    private $veritasClient;

    /** @var JanusClient */
    private $janusClient;

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
}