<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SymfonyOptionsResolverWrapper implements SymfonyOptionsResolverWrapperInterface
{
    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolverInterface
     */
    private $symfonyOptionsResolver;

    /**
     * @param OptionsResolverInterface $symfonyOptionsResolver
     */
    public function __construct(OptionsResolverInterface $symfonyOptionsResolver)
    {
        $this->symfonyOptionsResolver = $symfonyOptionsResolver;
    }

    /**
     * An array of options which must be specified. This replaces any previous required options set
     *
     * @param array $requiredOptions
     * @return $this
     */
    public function setRequired(array $requiredOptions = array())
    {
        $this->symfonyOptionsResolver->setRequired($requiredOptions);
        return $this;
    }

    /**
     * An array of optionName=>defaultValue pairs. This replaces the set of previously configured defaults
     *
     * @param array $defaultOptionValues
     * @return mixed
     */
    public function setDefaults(array $defaultOptionValues = array())
    {
        $this->symfonyOptionsResolver->setDefaults($defaultOptionValues);
        return $this;
    }
}
