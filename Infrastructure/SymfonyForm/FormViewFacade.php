<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\View\FormViewInterface;
use Symfony\Component\Form\FormView;

class FormViewFacade implements FormViewInterface
{
    /**
     * @var FormView
     */
    private $symfonyFormView;

    public function __construct(FormView $symfonyFormView)
    {
        $this->symfonyFormView = $symfonyFormView;
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getSymfonyFormView()
    {
        return $this->symfonyFormView;
    }
}
