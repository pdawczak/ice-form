<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\CppaStatement\V1;

use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\FormInterface as SymfonyFormInterface;

class CppaStatementType implements FormTypeInterface
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
        $builder->add('cppaStatement', 'textarea', [
            'label' => 'Please explain in your own words what you think the main challenge is facing public examinations and assessment today.  How can awarding organisations address this issue? (500 word limit).',
            'constraints' => [
                'not_blank' => [
                    'message' => 'You must provide this statement.'
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
            'data_class'=>'Ice\FormBundle\Process\CourseApplication\Step\CppaStatement\V1\CppaStatementData'
        ]);
    }


    public function getName()
    {
        return 'cppaStatement';
    }
}
