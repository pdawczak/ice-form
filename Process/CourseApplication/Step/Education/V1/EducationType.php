<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Education\V1;

use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;

class EducationType implements FormTypeInterface
{
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Called when we're building a form instance of this type with given options. Use the builder to add any children,
     * etc as necessary.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->enableHighestQualificationQuestion()) {
            $builder->add('highestQualification', 'textarea', [
                'label' => 'If you have an academic qualification please indicate the highest that you currently hold. If degree, please state: Degree, Subject, Class, University/Institution and Year awarded. If other qualification: Level, Subject, Class or Mark and Institution. ',
            ]);
        }
        $builder->add('recentInvolvement', 'textarea', [
            'label'=>'Please describe briefly your recent involvement in the subject area, if any, e.g. courses attended (title/level/date), practical work undertaken, qualifications gained (subject/level/date) etc. and other information in support of your application.',
            'constraints' => [
                'not_blank' => [
                    'message' => 'Please describe your recent involvement in the subject area'
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
            'data_class'=>'Ice\FormBundle\Process\CourseApplication\Step\Education\V1\EducationData'
        ]);
    }


    public function getName()
    {
        return 'education';
    }

    private function enableHighestQualificationQuestion()
    {
        list($first, $second, $third) = explode('.', $this->version);

        return $second < 1;
    }
}
