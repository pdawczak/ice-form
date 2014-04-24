<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

interface SymfonyOptionsToNativeOptionsTransformerInterface
{
    /**
     * @param OptionsResolverInterface $symfonyOptionsResolver
     * @return SymfonyOptionsResolverWrapperInterface
     */
    public function transformToNativeOptions(OptionsResolverInterface $symfonyOptionsResolver);
}
