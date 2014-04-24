<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Symfony\Component\Form\FormInterface as SymfonyFormInterface;

interface SymfonyFormWrapperFactoryInterface
{
    /**
     * @param SymfonyFormInterface $symfonyForm
     * @return SymfonyFormWrapperInterface
     */
    public function getSymfonyFormWrapper(SymfonyFormInterface $symfonyForm);
}
