<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\FormInterface;

class SymfonyFormWrapper implements FormInterface, SymfonyFormWrapperInterface
{
    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $symfonyForm;

    /**
     * @param \Symfony\Component\Form\FormInterface $symfonyForm
     */
    public function __construct(\Symfony\Component\Form\FormInterface $symfonyForm)
    {
        $this->symfonyForm = $symfonyForm;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSymfonyForm()
    {
        return $this->symfonyForm;
    }

    public function bind($request)
    {
        $this->symfonyForm->bind($request);
    }

    public function isValid()
    {
        return $this->symfonyForm->isValid();
    }
}
