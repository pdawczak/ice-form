<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\HesaInformation;

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

    protected $childFormOrder = [
        2 => 'hesaEthnicOrigin',
        3 => 'hesaPreviouslyStudiedAtDegreeLevel',
        4 => 'hesaParentalQualifications',
        5 => 'hesaHighestQualification',
        6 => 'hesaMostRecentEducationInstitutionType',
        7 => 'hesaMostRecentEducationInstitutionName',
        8 => 'hesaFeeSource',
        9 => 'hesaFeesEmployerType'
    ];

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
        parent::buildForm($builder, $options);
    }

    public function getHtmlTemplate()
    {
        return 'HesaInformation.html.twig';
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
                return $groups;
            }
        ));
    }
}