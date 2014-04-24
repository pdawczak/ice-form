<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Symfony\Component\Form\FormBuilderInterface as SymfonyFormBuilderInterface;
use Ice\FormBundle\Form\Builder\FormBuilderInterface;

interface SymfonyFormBuilderToNativeFormBuilderTransformerInterface
{
    /**
     * @param SymfonyFormBuilderInterface $symfonyFormBuilder
     * @return FormBuilderInterface
     */
    public function transformToNativeBuilder(SymfonyFormBuilderInterface $symfonyFormBuilder);
}
