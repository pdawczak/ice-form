<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\Builder\FormBuilderFactoryInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface as SymfonyFormBuilderInterface;

class FormBuilderFactory implements FormBuilderFactoryInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $symfonyFormFactory;

    /**
     * @var SymfonyFormWrapperFactoryInterface
     */
    private $symfonyFormWrapperFactory;

    /**
     * @var NativeFormTypeToSymfonyFormTypeTransformerInterface
     */
    private $typeTransformer;

    public function __construct(
        \Symfony\Component\Form\FormFactoryInterface $symfonyFormFactory,
        SymfonyFormWrapperFactoryInterface $symfonyFormWrapperFactory,
        NativeFormTypeToSymfonyFormTypeTransformerInterface $typeTransformer
    )
    {
        $this->symfonyFormFactory = $symfonyFormFactory;
        $this->symfonyFormWrapperFactory = $symfonyFormWrapperFactory;
        $this->typeTransformer = $typeTransformer;
    }

    /**
     * Returns a form builder
     *
     * @param string|FormTypeInterface $type    The type of the form
     * @param mixed $data    The initial data
     * @param array $options The options
     *
     * @return FormBuilderInterface The form named after the type
     */
    public function createBuilder($type = 'form', $data = null, array $options = array())
    {
        return new FormBuilderFacade(
            $this->symfonyFormFactory->createBuilder(
                $this->resolveTypeToSymfonyType($type),
                $data,
                $options
            ),
            $this->symfonyFormWrapperFactory,
            $this->typeTransformer
        );
    }


    private function resolveTypeToSymfonyType($type)
    {
        if (!($type instanceof \Symfony\Component\Form\FormTypeInterface)) {
            return $this->typeTransformer->transformToSymfonyType($type);
        }
        return $type;
    }
}
