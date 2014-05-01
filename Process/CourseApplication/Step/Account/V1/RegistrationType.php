<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Account\V1;

use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;

class RegistrationType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);
        $builder
            ->add('title', 'text')
            ->add('firstNames', 'text',
                [
                    'constraints' => [
                        'length' => [
                            'min' => 3
                        ]
                    ]
                ]
            )
            ->add('lastName', 'text')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match',
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'constraints' => [
                    'not_blank',
                    'length' => [
                        'min' => 3
                    ]
                ]
            ))
        ;
        $builder->add('emailAddress', 'text', ['label'=>'Email address']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'child';
    }

    public function configureOptions(FormOptionsConfigurationInterface $optionConfiguration)
    {
        $optionConfiguration->setRequired(['step']);
        $optionConfiguration->setDefaults([
            'data_class'=>'Ice\FormBundle\Process\CourseApplication\Step\Account\V1\AccountData',
            'error_bubbling'=>true
        ]);
    }
}
