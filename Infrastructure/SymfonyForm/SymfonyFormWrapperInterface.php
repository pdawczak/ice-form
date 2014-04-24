<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

interface SymfonyFormWrapperInterface
{
    /**
     * Return the underlying Symfony FormInterface instance
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSymfonyForm();
}
