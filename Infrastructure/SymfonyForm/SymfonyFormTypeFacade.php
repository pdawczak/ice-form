<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SymfonyFormTypeFacade extends AbstractType
{
    /**
     * @var FormTypeInterface
     */
    private $nativeFormType;

    /**
     * @var SymfonyFormBuilderToNativeFormBuilderTransformerInterface
     */
    private $builderTransformer;

    /**
     * @param FormTypeInterface $nativeFormType
     * @param SymfonyFormBuilderToNativeFormBuilderTransformerInterface $builderTransformer
     */
    public function __construct(
        FormTypeInterface $nativeFormType,
        SymfonyFormBuilderToNativeFormBuilderTransformerInterface $builderTransformer
    )
    {
        $this->nativeFormType = $nativeFormType;
        $this->builderTransformer = $builderTransformer;
    }

    /**
     * Returns the reference of this type.
     *
     * @return string The reference of this type
     */
    public function getName()
    {
       return $this->nativeFormType->getName();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nativeBuilder = $this->builderTransformer->transformToNativeBuilder(
            $builder
        );
        $this->nativeFormType->buildForm($nativeBuilder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $symfonyOptionsResolver)
    {
        $this->nativeFormType->configureOptions(new OptionsResolverFacade($symfonyOptionsResolver));
    }
}
