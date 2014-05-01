<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Sponsor\V1;

use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;
use Symfony\Component\Form\FormInterface as SymfonyFormInterface;

class SponsorType implements FormTypeInterface
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
        $builder->add('sponsorPayment', 'choice', [
            'label' => 'Do you intend for your course fees to be paid by a sponsor organisation?',
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                'Y' => 'Yes',
                'N' => 'No'
            ],
            'constraints' => [
                'not_blank'
            ]
        ]);

        $builder->add('sponsorName', 'text', [
            'label' => 'Organisation name',
            'required' => false,
            'constraints' => [
                'not_blank' => [
                    'groups' => ['enable_sponsor']
                ]
            ]
        ]);


        $builder->add('sponsorAddress', 'textarea', [
            'label' => 'Invoice address',
            'required' => false
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
            'data_class'=>'Ice\FormBundle\Process\CourseApplication\Step\Sponsor\V1\SponsorData',
            'validation_groups' => function (SymfonyFormInterface $form) {
                /** @var $data SponsorData */
                $data = $form->getData();
                $groups = array('Default');

                if ($data->getSponsorPayment() === 'Y') {
                    $groups[] = 'enable_sponsor';
                }
                return $groups;
            }
        ]);
    }


    public function getName()
    {
        return 'sponsor';
    }
}
