<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\SupportNeeds;

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

class SupportNeedsType extends AbstractRegistrationStep{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('additionalNeeds', 'choice', array(
                    'choices'=>array('Yes', 'No'),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'Do you have any additional needs in relation to your learning or access to this course that you would like us to help you with, if we are able to do so?'
                )
            )
            ->add('additionalNeedsDetail', 'textarea', array(
                'label'=>'Please specify your additional needs, e.g. induction loop, large print, braille, wheelchair access'
            ))
            ->add('firstFloorAccess', 'choice', array(
                    'choices'=>array(
                        true=>'I AM able to access the first floor without assistance',
                        false=>'I AM NOT able to access the first floor without assistance'
                    ),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'Are you able to access the first floor without assistance?'
                )
            )
            ->add('shareSupportNeeds', 'choice', array(
                    'choices'=>array('Yes', 'No'),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'Do you consent to this information being passed on to appropriate parties, such as the course administrator or tutor?'
                )
            )
            ;
    }

    public function getTemplate(){
        return 'SupportNeeds.html.twig';
    }

    public function prepare(){
        $this->setEntity(new SupportNeeds());
        $this->setPrepared();
    }
}