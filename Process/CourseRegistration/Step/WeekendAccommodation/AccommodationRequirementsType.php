<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class AccommodationRequirementsType extends AbstractType implements FormTypeInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adaptedBedroomRequired', 'choice', array(
                'choices'     => array(
                    'Y' => 'Yes',
                    'N' => 'No'
                ),
                'expanded'    => true,
                'multiple'    => false,
                'label'       => 'Do you require a bedroom adapted for wheelchair use?',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'This question is required.',
                    )),
                    new Choice(array(
                        'choices' => array(
                            'Y',
                            'N'
                        ),
                        'message' => 'This question is required.',
                    )),
                )
            ))
            ->add('accommodationRequirements', 'textarea', array(
                'label'    => 'Please state any accommodation requirements you have.',
                'required' => false,
            ));
    }

    public function getName()
    {
        return 'accommodationRequirementsGroup';
    }
}
