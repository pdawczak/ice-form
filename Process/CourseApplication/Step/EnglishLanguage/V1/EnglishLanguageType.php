<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\EnglishLanguage\V1;

use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\FormInterface as SymfonyFormInterface;
use Ice\FormBundle\Type\CountryType;

class EnglishLanguageType implements FormTypeInterface
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
        $builder->add('englishFirstLanguage', 'choice', [
            'label' => 'Is English your first language?',
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                'Y' => 'Yes',
                'N' => 'No'
            ],
            'constraints' => [
                'not_blank' => [
                    'message' => 'Please indicate whether English is your first language',
                ]
            ]
        ]);
        $builder->add('englishTest', 'choice', [
            'label' => 'Have you taken an English language proficiency test in the last two years?',
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                'ielts' => 'IELTS',
                'cae_cpa' => 'CAE or CPA',
                'none' => 'No current language proficiency test'
            ],
            'required' => false,
            'constraints' => [
                'not_blank' => [
                    'groups' => ['enable_english_test']
                ]
            ]
        ]);
        $builder->add('trfNumber', 'text', [
            'label' => 'Please enter your TRF number',
            'required' => false,
            'constraints' => [
                'not_blank' => [
                    'message' => 'Please enter a TRF number',
                    'groups' => ['enable_ielts']
                ]
            ]
        ]);
        $builder->add('caeCpaIdNumber', 'text', [
            'label' => 'Please enter your candidate ID number',
            'required' => false,
            'constraints' => [
                'not_blank' => [
                    'message' => 'Please enter your candidate ID number',
                    'groups' => ['enable_caecpa']
                ]
            ]
        ]);
        $builder->add('caeCpaSecret', 'text', [
            'label' => 'Please enter your secret number',
            'required' => false,
            'constraints' => [
                'not_blank' => [
                    'message' => 'Please enter your secret number',
                    'groups' => ['enable_caecpa']
                ]
            ]
        ]);
        $builder->add('noTestAgreement', 'checkbox', [
            'label' => 'I agree to send my English language proficiency test results or reference details to the Institute of Continuing Education as soon as possible.',
            'required' => false,
            'constraints' => [
                'not_blank' => [
                    'message' => 'You must agree to send test results',
                    'groups' => ['enable_no_test']
                ]
            ]
        ]);

        if ($this->enableNationalityQuestion()) {
            $builder->add('nationality', new CountryType(), array(
                'empty_value' => 'Information refused',
                'required' => false
            ));
        }

        if ($this->enableQualificationsQuestion()) {
            $builder->add('qualifications', 'textarea', array(
                'label' => 'Qualifications that you hold at degree level or above. Include start and end years of study, where you studied and grade.',
                'required' => false,
                'constraints' => [
                    'not_blank' => [
                        'message' => 'Please specify any qualifications, or type \'None\'',
                        'groups' => ['enable_english_test']
                    ]
                ]
            ));
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
        $optionConfiguration->setRequired(['step']);
        $optionConfiguration->setDefaults([
            'data_class'=>'Ice\FormBundle\Process\CourseApplication\Step\EnglishLanguage\V1\EnglishLanguageData',
            'validation_groups' => function (SymfonyFormInterface $form) {
                /** @var $data EnglishLanguageData */
                $data = $form->getData();
                $groups = array('Default');

                if ($data->getEnglishFirstLanguage() === 'N') {
                    $groups[] = 'enable_english_test';

                    if ($data->getEnglishTest() === 'ielts') {
                       $groups[] = 'enable_ielts';
                    } else if ($data->getEnglishTest() === 'cae_cpa') {
                        $groups[] = 'enable_caecpa';
                    } else if ($data->getEnglishTest() === 'none') {
                        $groups[] = 'enable_no_test';
                    }
                }
                return $groups;
            }
        ]);
    }


    public function getName()
    {
        return 'education';
    }


    private function enableNationalityQuestion()
    {
        list($first, $second, $third) = explode('.', $this->version);

        return $second > 0;
    }

    private function enableQualificationsQuestion()
    {
        list($first, $second, $third) = explode('.', $this->version);

        return $second > 0;
    }
}
