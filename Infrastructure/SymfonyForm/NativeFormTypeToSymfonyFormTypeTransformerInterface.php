<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\FormTypeInterface as SymfonyFormTypeInterface;

interface NativeFormTypeToSymfonyFormTypeTransformerInterface
{
    /**
     * @param FormTypeInterface $nativeType
     * @return SymfonyFormTypeInterface
     */
    public function transformToSymfonyType(FormTypeInterface $nativeType);
}
