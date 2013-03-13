<?php

namespace Ice\FormBundle\Process;

use Symfony\Component\Form\FormFactory;

use Symfony\Bundle\TwigBundle\TwigEngine;

class AbstractProcess{
    /** @var string */
    private $iceId;

    /** @var FormFactory */
    private $formFactory;

    /** @var TwigEngine */
    private $templating;

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
}