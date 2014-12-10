<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Bursary\V2;

use Ice\FormBundle\Entity\AvailableBursary;
use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\FormInterface as SymfonyFormInterface;

class BursaryType implements FormTypeInterface
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
        $builder->add('bursaryWishToApply', 'choice', [
            'label' => 'Do you wish to apply for bursary funding?',
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                'Y' => 'Yes',
                'N' => 'No'
            ],
            'constraints' => [
                'not_blank' => [
                    'message' => 'You must indicate whether you wish to apply'
                ]
            ]
        ]);

        if (isset($options['bursaries']['irh'])) {
            $builder->add('previousUniStudy', 'choice', [
                'label' => 'Have you previously studied at university level?',
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'choices' => [
                    'Y' => 'Yes',
                    'N' => 'No'
                ],
                'constraints' => [
                    'not_blank' => [
                        'groups' => ['wish_to_apply']
                    ]
                ]
            ]);

            $builder->add('irhStatement', 'textarea', [
                'label' => 'If you wish to be considered for the Ivy Rose Hood bursary, please state in not more than 500 words, why you believe you should be considered for this bursary and your reasons for wanting to undertake the course. Otherwise, please leave this section blank.',
                'required' => false
            ]);
        }

        if (isset($options['bursaries']['cup'])) {

            $builder->add('ukSchoolTeaching', 'choice', [
                'label' => 'Do you teach at a UK state school or state-funded FE institution?',
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'choices' => [
                    'Y' => 'Yes',
                    'N' => 'No'
                ],
                'constraints' => [
                    'not_blank' => [
                        'groups' => ['wish_to_apply']
                    ]
                ]
            ]);

            $builder->add('schoolContact', 'textarea', [
                'label' => 'Please provide the name and address of your institution, along with a contact name and email address. By providing this information you consent to the Institute of Continuing Education contacting your school/FE institution to confirm your teaching status.',
                'required' => false
            ]);
        }
    }

    /**
     * Called when the array of options is being put together in order to instantiate a form of this type. Use the
     * given $optionConfiguration instance to set defaults and specify which options are required.
     *
     * @param FormOptionsConfigurationInterface $optionConfiguration
     */
    public function configureOptions(FormOptionsConfigurationInterface $optionConfiguration)
    {
        $optionConfiguration->setRequired([
            'step',
            'bursaries'
        ]);

        $optionConfiguration->setDefaults([
            'data_class'=>'Ice\FormBundle\Process\CourseApplication\Step\Bursary\V2\BursaryData',
            'validation_groups' => function (SymfonyFormInterface $form) {
                /** @var $data BursaryData */
                $data = $form->getData();
                $groups = array('Default');

                if ($data->getBursaryWishToApply() === 'Y') {
                    $groups[] = 'wish_to_apply';
                }
                return $groups;
            }
        ]);
    }


    public function getName()
    {
        return 'bursary';
    }
}
