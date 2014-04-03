<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\HesaInformation;

use Ice\FormBundle\Process\CourseRegistration;
use Ice\FormBundle\Type\DisabilityType;
use Ice\FormBundle\Type\EducationInstitutionType;
use Ice\FormBundle\Type\EmployerTypeType;
use Ice\FormBundle\Type\EthnicityType;
use Ice\FormBundle\Type\FeeSourceType;
use Ice\FormBundle\Type\QualificationLevelType;
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

class HesaInformationType extends AbstractRegistrationStep
{

    protected $childFormOrder = [];

    public function __construct(CourseRegistration $parentProcess, $reference = null, $version = null)
    {
        $fieldNames = [
            'hesaEthnicOrigin',
            'hesaPreviouslyStudiedAtDegreeLevel',
            'hesaParentalQualifications',
            'hesaHighestQualification',
            'hesaMostRecentEducationInstitutionType',
            'hesaMostRecentEducationInstitutionName',
            'hesaFeeSource',
            'hesaFeesEmployerType'
        ];

        if ($this->enableDisabilityQuestions()) {
            $fieldNames[] = 'disabilityListed';
            $fieldNames[] = 'inReceiptOfDisabledStudentsAllowance';
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
        $builder
            ->add('hesaEthnicOrigin', new EthnicityType(), array(
                'label' => 'Please select the appropriate ethnic origin to indicate your background. If you do not wish to provide this information, select \'Information refused\'.',
                'empty_value' => '',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('hesaPreviouslyStudiedAtDegreeLevel', 'choice', array(
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'label' => 'Have you previously studied a course at degree level for six months or more in the UK?',
                'choices' => array(
                    'A' => 'Yes',
                    'B' => 'No',
                ),
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('hesaParentalQualifications', 'choice', array(
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'label' => 'Do any of your parents (natural or adoptive), step-parents or guardians have any higher education qualifications?',
                'choices' => array(
                    '1' => 'Yes',
                    '2' => 'No',
                    '8' => 'Do not know',
                    '9' => 'Information refused',
                ),
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('hesaHighestQualification', new QualificationLevelType(), array(
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'label' => 'What is the highest qualification you currently hold?',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('hesaMostRecentEducationInstitutionType', new EducationInstitutionType(), array(
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'label' => 'Please indicate your most recent education institution.',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('hesaMostRecentEducationInstitutionName', 'text', array(
                'label' => 'If you selected UK Higher Education institution, please state which one.',
                'required' => false,
                'constraints' => array(
                    new NotBlank(array(
                        'groups'=>['uk_higher_ed']
                    ))
                )
            ))
            ->add('hesaFeeSource', new FeeSourceType(), array(
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'label' => 'If your fees are being completely or partially funded by a third party (excluding an ICE bursary), please state the source. If your fees are not funded by a third party please select \'No award or financial backing\'.',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('hesaFeesEmployerType', new EmployerTypeType(), array(
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'empty_value' => '',
                'label' => 'If your employer is funding all or part of your fee, please state the type of employer.',
                'constraints' => array()
            ));

        if ($this->enableDisabilityQuestions()) {
            $builder
                ->add('disabilityListed', new DisabilityType(), array(
                    'label' => 'Please indicate any disability you may have. If you do not have a disability, special needs or a
                    medical condition, use \'No disability\'. If you do not wish to provide any information in this section,
                    use \'Information refused\'.',
                    'empty_value' => ''
                ))
                ->add('inReceiptOfDisabledStudentsAllowance', 'choice', array(
                    'label' => 'Please state whether you will be in receipt of Disabled Students\' Allowance (DSA) for the
                        purpose of studying this course.',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'choices' => array(
                        '4' => 'In receipt of Disabled Students Allowance',
                        '5' => 'Not in receipt of Disabled Students Allowance'
                    ),
                    'empty_value' => ''
                ))
            ;
        }

        parent::buildForm($builder, $options);
    }

    public function getHtmlTemplate()
    {
        return 'HesaInformation.html.twig';
    }

    public function getJavaScriptTemplate()
    {
        return 'HesaInformation.js.twig';
    }


    /**
     * @return mixed|void
     */
    public function prepare()
    {
        $this->setEntity(HesaInformation::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }

    public function getTitle()
    {
        return 'Identity and background';
    }

    private function enableDisabilityQuestions()
    {
        return $this->version != 1;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'validation_groups' => function (FormInterface $form) {
                /** @var $data HesaInformation */
                $data = $form->getData();
                $groups = ['Default'];
                if (intval($data->getHesaMostRecentEducationInstitutionType()) === 4941) {
                    $groups[] = 'uk_higher_ed';
                }
                if ($this->enableDisabilityQuestions()) {
                    $groups[] = 'ask_disability';
                    if (!in_array(intval($data->getDisabilityListed()), [0, 97])) {
                        $groups[] = 'ask_dsa';
                    }
                }
                return $groups;
            }
        ));
    }
}