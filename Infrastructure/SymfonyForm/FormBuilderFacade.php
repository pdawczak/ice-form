<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\FormTypeInterface as SymfonyFormTypeInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class FormBuilderFacade implements FormBuilderInterface
{
    /**
     * @var \Symfony\Component\Form\FormBuilderInterface
     */
    private $symfonyFormBuilder;

    /**
     * @var SymfonyFormWrapperFactory
     */
    private $formWrapperFactory;

    /**
     * @var NativeFormTypeToSymfonyFormTypeTransformerInterface
     */
    private $typeTransformer;

    public function __construct(
        \Symfony\Component\Form\FormBuilderInterface $symfonyFormBuilder,
        SymfonyFormWrapperFactoryInterface $symfonyFormWrapperFactory,
        NativeFormTypeToSymfonyFormTypeTransformerInterface $typeTransformer
    )
    {
        $this->symfonyFormBuilder = $symfonyFormBuilder;
        $this->formWrapperFactory = $symfonyFormWrapperFactory;
        $this->typeTransformer = $typeTransformer;
    }

    /**
     * Adds a new field to this group. A field must have a unique reference within
     * the group. Otherwise the existing field is overwritten.
     *
     * If you add a nested group, this group should also be represented in the
     * object hierarchy.
     *
     * @param string|integer|FormBuilderInterface $child
     * @param string|FormTypeInterface $type
     * @param array $options
     *
     * @return FormBuilderInterface The builder object.
     */
    public function add($child, $type = null, array $options = array())
    {
        $symfonyOptions = $options;

        if (isset($options['constraints'])) {
            $symfonyOptions['constraints'] = [];
            foreach ($options['constraints'] as $name => $constraintOptions) {
                $symfonyConstraint = null;

                //Constraints may just be named and have no options, in which case the name will be the array value
                //and the key will be an integer.
                if (is_integer($name) && is_string($constraintOptions)) {
                    $name = $constraintOptions;
                    $constraintOptions = [];
                }

                switch ($name) {
                    case 'length':
                        $symfonyConstraint = new Length($constraintOptions);
                        break;
                    case 'not_blank':
                        $symfonyConstraint = new NotBlank($constraintOptions);
                        break;
                    default:
                        throw new \Exception("Unrecognised constraint: ".$name);
                }
                $symfonyOptions['constraints'][] = $symfonyConstraint;
            }
            unset($options['constraints']);
        }

        $this->symfonyFormBuilder->add(
            $child,
            $this->resolveTypeToSymfonyType($type),
            $symfonyOptions
        );
        return $this;
    }

    private function resolveTypeToSymfonyType($type)
    {
        if (!($type instanceof SymfonyFormTypeInterface)) {
            if ($type instanceof FormTypeInterface) {
                return $this->typeTransformer->transformToSymfonyType($type);
            }
        }
        return $type;
    }

    public function getForm()
    {
        return $this->formWrapperFactory->getSymfonyFormWrapper(
            $this->symfonyFormBuilder->getForm()
        );
    }
}
