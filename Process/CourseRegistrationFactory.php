<?php

namespace Ice\FormBundle\Process;

use Ice\FormBundle\Process\CourseRegistration\StepDirectorInterface;
use Ice\JanusClientBundle\Service\JanusClient;
use Ice\MinervaClientBundle\Service\MinervaClient;
use Ice\VeritasClientBundle\Service\VeritasClient;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Form\FormFactoryInterface;

class CourseRegistrationFactory implements CourseRegistrationFactoryInterface
{
    /**
     * @var CourseRegistration\StepDirectorInterface
     */
    private $stepDirector;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Ice\JanusClientBundle\Service\JanusClient
     */
    private $janusClient;

    /**
     * @var \Ice\MinervaClientBundle\Service\MinervaClient
     */
    private $minervaClient;

    /**
     * @var \Ice\VeritasClientBundle\Service\VeritasClient
     */
    private $veritasClient;

    /**
     * @var \Symfony\Bridge\Twig\TwigEngine
     */
    private $templating;

    /**
     * @param StepDirectorInterface $stepDirector
     * @param FormFactoryInterface $formFactory
     * @param TwigEngine $templating
     * @param JanusClient $janusClient
     * @param MinervaClient $minervaClient
     * @param VeritasClient $veritasClient
     */
    public function __construct(
        StepDirectorInterface $stepDirector,
        FormFactoryInterface $formFactory,
        TwigEngine $templating,
        JanusClient $janusClient,
        MinervaClient $minervaClient,
        VeritasClient $veritasClient
    )
    {
        $this->stepDirector = $stepDirector;
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->janusClient = $janusClient;
        $this->minervaClient = $minervaClient;
        $this->veritasClient = $veritasClient;
    }

    /**
     * Return a CourseRegistration instance linked to the specified courseId and (if applicable) registrant ICE id
     *
     * @param $courseId
     * @param null $registrantId
     * @return CourseRegistration
     */
    public function getCourseRegistrationProcess($courseId, $registrantId = null)
    {
        $courseRegistration = new CourseRegistration($this->stepDirector);
        $courseRegistration
            ->setCourseId($courseId)
            ->setFormFactory($this->formFactory)
            ->setTemplating($this->templating)
            ->setVeritasClient($this->veritasClient)
            ->setJanusClient($this->janusClient)
            ->setMinervaClient($this->minervaClient);

        if ($registrantId) {
            $courseRegistration->setRegistrantId($registrantId);
        }

        return $courseRegistration;
    }
}
