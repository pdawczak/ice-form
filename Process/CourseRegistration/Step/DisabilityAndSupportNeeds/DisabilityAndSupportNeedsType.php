<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\DisabilityAndSupportNeeds;

use Ice\FormBundle\Process\CourseRegistration;
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

class DisabilityAndSupportNeedsType extends AbstractRegistrationStep
{
    protected $childFormOrder; //Must be set in the constructor

    public function __construct(CourseRegistration $parentProcess, $reference = null, $version = null)
    {
        $fieldNames = [];

        if ($this->enableHesaDisabilityQuestions()) {
            $fieldNames[] = 'disabilityListed';
            $fieldNames[] = 'inReceiptOfDisabledStudentsAllowance';
        }

        $fieldNames[] = 'additionalNeeds';

        if ($this->enableShareSupportNeeds()) {
            $fieldNames[] = 'additionalNeedsDetail';
        }

        if ($this->enableFirstFloorAccess()) {
            $fieldNames[] = 'firstFloorAccess';
        }

        if ($this->enableShareSupportNeeds()) {
            $fieldNames[] = 'shareSupportNeeds';
        }

        $order = 1;
        foreach ($fieldNames as $fieldName) {
            $this->childFormOrder[$order] = $fieldName;
            $order++;
        }

        parent::__construct($parentProcess, $reference, $version);
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->enableHesaDisabilityQuestions()) {
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
            ;
        }
        $builder
            ->add('additionalNeeds', 'choice', array(
                    'choices' => array(
                        'Y' => 'Yes',
                        'N' => 'No'
                    ),
                    'expanded' => true,
                    'multiple' => false,
                    'label' => 'Do you have any additional requirements in relation to your learning or access to this course?'
                )
            )
        ;

        if ($this->enableAdditionalNeedsDetail()) {
            $builder
                ->add('additionalNeedsDetail', 'textarea', array(
                        'label' => 'Please specify your requirements, e.g. induction loop, large print, braille, wheelchair access',
                        'required' => false,
                    )
                )
            ;
        }

        if ($this->enableFirstFloorAccess()) {
            $builder
                ->add('firstFloorAccess', 'choice', array(
                        'choices' => array(
                            'Y' => 'I AM able to access the first floor without assistance',
                            'N' => 'I AM NOT able to access the first floor without assistance'
                        ),
                        'expanded' => true,
                        'multiple' => false,
                        'label' => 'Are you able to access the first floor without assistance?'
                    )
                )
            ;
        }

        if ($this->enableShareSupportNeeds()) {
            $builder
                ->add('shareSupportNeeds', 'choice', array(
                        'choices' => array(
                            'Y' => 'Yes',
                            'N' => 'No'
                        ),
                        'expanded' => true,
                        'multiple' => false,
                        'label' => 'Do you consent to this information being passed on to appropriate parties, such as the course administrator or tutor?'
                    )
                )
            ;
        }
        parent::buildForm($builder, $options);
    }

    private function enableHesaDisabilityQuestions()
    {
        return ($this->version == 1) &&
            in_array($this->getReference(), ['disabilityAndSupportNeeds', 'nonMadingleyDisabilityAndSupportNeeds']);
    }

    private function enableAdditionalNeedsDetail()
    {
        return ($this->version == 1) || in_array($this->getReference(), ['madingleySupportNeeds', 'supportNeeds']);
    }

    private function enableShareSupportNeeds()
    {
        return $this->version == 1;
    }

    private function enableFirstFloorAccess()
    {
        return in_array($this->getReference(), ['disabilityAndSupportNeeds', 'madingleySupportNeeds']);
    }

    public function getHtmlTemplate()
    {
        return 'DisabilityAndSupportNeeds.html.twig';
    }

    public function getJavaScriptTemplate()
    {
        return 'DisabilityAndSupportNeeds.js.twig';
    }

    public function getTitle()
    {
        if ($this->enableHesaDisabilityQuestions()) {
            return 'Disability and support needs';
        }
        return 'Support requirements';
    }

    public function prepare()
    {
        $this->setEntity(DisabilityAndSupportNeeds::fromStepProgress($this->getStepProgress()));
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
                /** @var $data DisabilityAndSupportNeeds */
                $data = $form->getData();
                $groups = array('Default');

                if ($data->getAdditionalNeeds() === 'Y' && $this->enableAdditionalNeedsDetail()) {
                    $groups[] = 'enable_additional_needs_detail';
                }
                if ($this->enableShareSupportNeeds()) {
                    $groups[] = 'enable_share_support_needs';
                }
                if ($this->enableFirstFloorAccess()) {
                    $groups[] = 'enable_first_floor_access';
                }
                if ($this->enableHesaDisabilityQuestions()) {
                    $groups[] = 'enable_hesa';
                }
                return $groups;
            }
        ));
    }


}