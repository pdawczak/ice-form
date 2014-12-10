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
            ->add('title', 'choice', [
                'label' => 'Title',
                'multiple' => false,
                'expanded' => false,
                'choices' => array(
                    'Mr' => 'Mr',
                    'Mrs' => 'Mrs',
                    'Miss' => 'Miss',
                    'Ms' => 'Ms',
                    'Dr' => 'Dr',
                    'Prof' => 'Prof',
                    'Revd' => 'Revd',
                    'Misc' => 'Misc',
                    'Mx' => 'Mx',
                )
            ])
            ->add('firstNames', 'text', [
                'label' => 'First name(s)',
                'constraints' => [
                    'not_blank'
                ]
            ])
            ->add('lastName', 'text', [
                'label' => 'Last name',
                'constraints' => [
                    'not_blank'
                ]
            ])
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match',
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'constraints' => [
                    'not_blank',
                    'length' => [
                        'min' => 6
                    ]
                ]
            ))
            ->add('dob', 'birthday', array(
                'label' => 'Date of birth',
                'input' => "datetime",
                'widget' => "choice",
                'format' => 'd MMM yyyy',
                'constraints' => ['not_blank'],
                'empty_value' => array(
                    'day' => 'Day',
                    'month' => 'Month',
                    'year' => 'Year',
                ),
            ))
            ->add('sex', 'choice', array(
                'label' => 'Gender',
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'choices' => array(
                    'm' => 'Male',
                    'f' => 'Female',
                    'o' => 'Other'
                ),
            ))
        ;
        $builder->add('emailAddress', 'text', ['label'=>'Email address', 'constraints'=>['not_blank']]);
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
