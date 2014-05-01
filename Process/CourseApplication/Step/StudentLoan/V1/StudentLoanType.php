<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\StudentLoan\V1;

use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\FormInterface as SymfonyFormInterface;

class StudentLoanType implements FormTypeInterface
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
        $builder->add('studentLoanApplied', 'choice', [
            'label' => 'Please state whether you have applied for a student loan',
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                'received' => 'Yes, letter received - I have applied for a student loan, and have received my Student Entitlement Letter from Student Finance England',
                'applied' => 'Yes - I have applied for a student loan, and have not yet received my Student Entitlement Letter from Student Finance England',
                'N' => 'I will not be applying for a student loan'
            ],
            'constraints' => [
                'not_blank' => [
                    'message' => 'Please indicate whether you have applied for a student loan'
                ]
            ]
        ]);
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
            'data_class'=>'Ice\FormBundle\Process\CourseApplication\Step\StudentLoan\V1\StudentLoanData'
        ]);
    }


    public function getName()
    {
        return 'studentLoan';
    }
}
