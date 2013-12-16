<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\NonMadingleyDisabilityAndSupportNeeds;

use Ice\FormBundle\Type\DisabilityType;
use Ice\JanusClientBundle\Exception\ValidationException;
use JMS\Serializer\Tests\Fixtures\Person;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ice\JanusClientBundle\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NonMadingleyDisabilityAndSupportNeedsType extends AbstractRegistrationStep
{
    protected $childFormOrder = [
        2 => 'disabilityListed',
        3 => 'inReceiptOfDisabledStudentsAllowance',
        4 => 'additionalNeeds',
        5 => 'additionalNeedsDetail',
        6 => 'shareSupportNeeds',
    ];

    public function getTitle()
    {
        return 'Disability and support needs';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('disabilityListed', new DisabilityType(), array(
                'label' => 'Please select the appropriate option. If you do not have a disability, special needs or a
                medical condition, use \'No disability\'. If you do not wish to provide any information in this section,
                use \'Information refused\'.',
                'empty_value' => ''
            ))
            ->add('inReceiptOfDisabledStudentsAllowance', 'choice', array(
                'label' => 'Please state whether you will be in receipt of Disabled Students\' Allowance (DSA) for the
                    purpose of studying this course.',
                'expanded' => false,
                'multiple' => false,
                'choices' => array(
                    '4' => 'In receipt of Disabled Students Allowance',
                    '5' => 'Not in receipt of Disabled Students Allowance'
                ),
                'empty_value' => ''
            ))
            ->add('additionalNeeds', 'choice', array(
                    'choices' => array(
                        'Y' => 'Yes',
                        'N' => 'No'
                    ),
                    'expanded' => true,
                    'multiple' => false,
                    'label' => 'Do you have any additional needs in relation to your learning or access to this course that you would like us to help you with, if we are able to do so?'
                )
            )
            ->add('additionalNeedsDetail', 'textarea', array(
                    'label' => 'Please specify your additional needs, e.g. induction loop, large print, braille, wheelchair access',
                    'required' => false,
                )
            )
            ->add('shareSupportNeeds', 'choice', array(
                    'choices' => array(
                        'Y' => 'Yes',
                        'N' => 'No'
                    ),
                    'expanded' => true,
                    'multiple' => false,
                    'label' => 'Do you consent to this information being passed on to appropriate parties, such as the course administrator or tutor?'
                )
            );
        parent::buildForm($builder, $options);
    }

    public function getHtmlTemplate()
    {
        return 'NonMadingleyDisabilityAndSupportNeeds.html.twig';
    }

    public function getJavaScriptTemplate()
    {
        return 'NonMadingleyDisabilityAndSupportNeeds.js.twig';
    }


    public function prepare()
    {
        $this->setEntity(NonMadingleyDisabilityAndSupportNeeds::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'validation_groups' => function (FormInterface $form) {
                /** @var $data NonMadingleyDisabilityAndSupportNeeds */
                $data = $form->getData();
                $groups = array('Default');
                if ($data->getAdditionalNeeds() === 'Y') {
                    $groups[] = 'has_additional_needs';
                }
                return $groups;
            }
        ));
    }


}