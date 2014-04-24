<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\FormTypeInterface as SymfonyFormTypeInterface;
use Symfony\Component\Form\FormBuilderInterface as SymfonyFormBuilderInterface;
use Ice\FormBundle\Form\Builder\FormBuilderInterface;

/**
 * NB: This class breaks SRP to get around what would otherwise be a circular dependency: Anything wrapping FormBuilder
 * needs in its 'add' method to be able to convert given native types to symfony types. Anything wrapping a native type
 * needs in its 'buildForm' method to be able to convert given a Symfony form builder to a native form builder.
 *
 * So FormBuilderFacade instantiation requires a NativeFormTypeToSymfonyFormTypeTransformerInterface which instantiates
 * a SymfonyFormTypeFacade requiring a SymfonyFormBuilderToNativeFormBuilderTransformerInterface which instantiates
 * FormBuilderFacades.
 *
 * This is ultimately because Types work by being given a Builder to add Types to, so the circularity is inherent in
 * Symfony and I can blame them for the tight coupling!
 *
 * I can't think of a neater way around this. The functionality of this class is kept to a bear minimum so that even
 * though it breaks SRP it's by no means a monster.
 *
 * ~rh389
 *
 * Class SymfonyNativeTransformer
 * @package Ice\FormBundle\Infrastructure\SymfonyForm
 */
class SymfonyNativeTransformer implements
    SymfonyFormBuilderToNativeFormBuilderTransformerInterface,
    NativeFormTypeToSymfonyFormTypeTransformerInterface
{
    /**
     * @var SymfonyFormWrapperFactoryInterface
     */
    private $symfonyFormWrapperFactory;

    /**
     * @param SymfonyFormWrapperFactoryInterface $symfonyFormWrapperFactory
     */
    public function __construct(SymfonyFormWrapperFactoryInterface $symfonyFormWrapperFactory)
    {
        $this->symfonyFormWrapperFactory = $symfonyFormWrapperFactory;
    }

    /**
     * @param SymfonyFormBuilderInterface $symfonyFormBuilder
     * @return FormBuilderInterface
     */
    public function transformToNativeBuilder(SymfonyFormBuilderInterface $symfonyFormBuilder)
    {
        return new FormBuilderFacade($symfonyFormBuilder, $this->symfonyFormWrapperFactory, $this);
    }

    /**
     * @param FormTypeInterface $nativeType
     * @return SymfonyFormTypeInterface
     */
    public function transformToSymfonyType(FormTypeInterface $nativeType)
    {
        return new SymfonyFormTypeFacade($nativeType, $this);
    }
}
