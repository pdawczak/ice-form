<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Symfony\Component\Form\FormInterface as SymfonyFormInterface;

class SymfonyFormWrapperFactory implements SymfonyFormWrapperFactoryInterface
{
    /**
     * @param SymfonyFormInterface $symfonyForm
     * @return SymfonyFormWrapperInterface
     */
    public function getSymfonyFormWrapper(SymfonyFormInterface $symfonyForm)
    {
        return new SymfonyFormWrapper($symfonyForm);
    }
}
