<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SymfonyOptionsToNativeOptionsTransformer implements SymfonyOptionsToNativeOptionsTransformerInterface
{
    /**
     * @param OptionsResolverInterface $symfonyOptionsResolver
     * @return SymfonyOptionsResolverWrapperInterface
     */
    public function transformToNativeOptions(OptionsResolverInterface $symfonyOptionsResolver)
    {
        return new SymfonyOptionsResolverWrapper($symfonyOptionsResolver);
    }
}
