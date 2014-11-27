<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\NationalityAndResidence;

use Ice\FormBundle\Process\CourseRegistration;
use Ice\FormBundle\Type\CamsisCountryType;
use Ice\FormBundle\Type\CountryType;
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

class NationalityAndResidenceType extends AbstractRegistrationStep
{
    protected $childFormOrder;

    public function __construct(CourseRegistration $registration, $reference, $version)
    {
        $fieldNames = [
            'countryOfResidence',
            'countryOfBirth',
            'primaryNationality',
            'secondaryNationality',
            'ordinarilyResident',
            'eeaOrSwissNational',
            'familyMemberEuNational',
            'settledInUk',
            'grantedRefugeeStatus'
        ];

        if ($this->enableHumanitarianProtectionQuestion()) {
            $fieldNames[] = 'grantedHumanitarianProtectionStatus';
        }

        $fieldNames[] = 'requireVisa';

        if ($this->enableVisaQuestion()) {
            $fieldNames[] = 'visaStatus';
        }

        $order = 1;
        foreach ($fieldNames as $fieldName) {
            $this->childFormOrder[$order] = $fieldName;
            $order++;
        }
        parent::__construct($registration, $reference, $version);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('countryOfResidence', new CamsisCountryType(), array(
                    'label' => 'Please select your country of permanent residence (where you normally live). If you live within
                        the UK, please select England, Scotland, Wales or Northern Ireland as appropriate',
                    'empty_value'=>'',
                )
            )
            ->add('countryOfBirth', new CountryType(), array(
                    'label' => 'Country of birth',
                    'empty_value'=>'',
                )
            )
            ->add('primaryNationality', new CountryType(), array(
                    'label' => 'Please select your nationality. If you have more than one nationality please select your primary nationality.',
                    'empty_value'=>'',
                )
            )
            ->add('secondaryNationality', new CountryType(), array(
                    'label' => 'If you have more than one nationality, you may enter a second nationality.',
                    'empty_value'=>'',
                    'required'=>false
                )
            )
            ->add('ordinarilyResident', 'choice', array(
                    'label' => 'Will you have been resident in any of the following for at least 3 full years prior to the first day of your course? (If you have, but the purpose of your residency was to receive full-time education at any point in the 3-year period, please select NO). If more than one applies, please select the most recent.',
                    'empty_value'=>'',
                    'required'=>false,
                    'expanded'=>false,
                    'multiple'=>false,
                    'choices'=>array(
                        'GBR' => 'UK',
                        'CHI' => 'Channel Islands',
                        'EU' => 'EU (outside UK)',
                        'ISL' => 'Iceland',
                        'LIE' => 'Lichtenstein',
                        'NOR' => 'Norway',
                        'CHE' => 'Switzerland',
                        'BOT' => 'British Overseas Territories',
                        'IMN' => 'Isle of Man',
                        'NO' => 'NO',
                    )
                )
            )
            ->add('eeaOrSwissNational', 'choice', array(
                    'label' => 'Are you a British, EEA (European Economic Area) or Swiss national living and working in the UK?',
                    'required'=>false,
                    'expanded'=>true,
                    'multiple'=>false,
                    'choices'=>array(
                        'Y' => 'Yes',
                        'N' => 'No',
                    )
                )
            )
            ->add('familyMemberEuNational', 'choice', array(
                    'label' => 'Are you a non-EU or non-UK citizen national who is a family member of an EU national?',
                    'required'=>false,
                    'expanded'=>true,
                    'multiple'=>false,
                    'choices'=>array(
                        'Y' => 'Yes',
                        'N' => 'No',
                    )
                )
            )
            ->add('settledInUk', 'choice', array(
                    'label' => 'Are you a non-UK/EU citizen settled in the UK?',
                    'expanded'=>true,
                    'multiple'=>false,
                    'choices'=>array(
                        'Y' => 'Yes',
                        'N' => 'No',
                    )
                )
            )
            ->add('grantedRefugeeStatus', 'choice', array(
                    'label' => 'Have you been granted refugee status in the UK?',
                    'expanded'=>true,
                    'multiple'=>false,
                    'choices'=>array(
                        'Y' => 'Yes',
                        'N' => 'No',
                    )
                )
            );

        if ($this->enableHumanitarianProtectionQuestion()) {
            $builder->add('grantedHumanitarianProtectionStatus', 'choice', array(
                    'label' => 'Have you been granted Humanitarian Protection?',
                    'expanded'=>true,
                    'multiple'=>false,
                    'choices'=>array(
                        'Y' => 'Yes',
                        'N' => 'No',
                    ),
                    'constraints'=>array(new NotBlank())
                )
            );
        }

        if ($this->enableVisaRequiredQuestion()) {
            $builder
                ->add('requireVisa', 'choice', array(
                        'label' => 'Do you require a visa to study in the UK? ',
                        'expanded'=>true,
                        'multiple'=>false,
                        'choices'=>array(
                            'Y' => 'Yes',
                            'N' => 'No',
                        )
                    )
                )
            ;
        }
        if ($this->enableVisaQuestion()) {
            $builder
                ->add('visaStatus', 'choice', array(
                        'label' => 'Please enter your current UK visa status, if applicable.',
                        'required'=>false,
                        'expanded'=>true,
                        'multiple'=>false,
                        'choices'=>array(
                            'Academic Visitor Visa',
                            'British Overseas Territories',
                            'Business Visitor Visa',
                            'General Visitor Visa',
                            'Indefinite Leave to Enter',
                            'Indefinite Leave to Remain',
                            'No UK Visa',
                            'Other',
                            'Refugee Status',
                            'Resident by Right',
                            'Tier 1 Visa',
                            'Tier 2 Visa',
                            'Tier 3 Visa',
                            'Tier 4 Visa (for Cambridge)',
                            'Tier 4 Visa (not Cambridge)',
                            'Tier 5 Visa',
                        )
                    )
                )
            ;
        }

        parent::buildForm($builder, $options);
    }

    public function enableHumanitarianProtectionQuestion()
    {
        return $this->version != 1;
    }

    public function enableVisaQuestion()
    {
        return $this->version == 1;
    }

    public function getHtmlTemplate()
    {
        return 'NationalityAndResidence.html.twig';
    }

    public function prepare()
    {
        $this->setEntity(NationalityAndResidence::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }

    private function enableVisaRequiredQuestion()
    {
        return $this->version < 3;
    }
}
