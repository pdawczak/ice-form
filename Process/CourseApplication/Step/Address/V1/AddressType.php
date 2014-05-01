<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Address\V1;

use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;
use Ice\FormBundle\Type\CountryType;

class AddressType implements FormTypeInterface
{
    /**
     * Called when we're building a form instance of this type with given options. Use the builder to add any children,
     * etc as necessary.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address1', 'text', ['label' => 'Address line 1', 'constraints' => ['not_blank']]);
        $builder->add('address2', 'text', ['label' => 'Address line 2','required' => false]);
        $builder->add('address3', 'text', ['label' => 'Address line 3','required' => false]);
        $builder->add('town', 'text', ['constraints' => ['not_blank']]);
        $builder->add('county', 'text', ['constraints' => ['not_blank']]);
        $builder->add('postcode', 'text', ['label' => 'Post code','constraints' => ['not_blank']]);
        $builder->add('country', new CountryType(), ['constraints' => ['not_blank']]);
        $builder->add('telephone', 'text', ['constraints' => ['not_blank']]);
    }

    /**
     * Called when the array of options is being put together in order to instantiate a form of this type. Use the
     * given $optionConfiguration instance to set defaults and specify which options are required.
     *
     * @param FormOptionsConfigurationInterface $optionConfiguration
     */
    public function configureOptions(FormOptionsConfigurationInterface $optionConfiguration)
    {
        $optionConfiguration->setRequired(['step']);
        $optionConfiguration->setDefaults([
            'data_class'=>'Ice\FormBundle\Process\CourseApplication\Step\Address\V1\AddressData'
        ]);
    }


    public function getName()
    {
        return 'address';
    }
}
