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
use Symfony\Component\Validator\Constraints;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ice\JanusClientBundle\Entity\User;


class SupportNeedsType extends AbstractRegistrationStep{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('additionalNeeds', 'choice', array(
                    'choices'=>array(
                        1=>'Yes',
                        0=>'No'
                    ),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'Do you have any additional needs in relation to your learning or access to this course that you would like us to help you with, if we are able to do so?'
                )
            )
            ->add('additionalNeedsDetail', 'textarea', array(
                    'label'=>'Please specify your additional needs, e.g. induction loop, large print, braille, wheelchair access',
                    'required'=>false,
                )
            );

        $builder
            ->add('firstFloorAccess', 'choice', array(
                    'choices'=>array(
                        1=>'I AM able to access the first floor without assistance',
                        0=>'I AM NOT able to access the first floor without assistance'
                    ),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'Are you able to access the first floor without assistance?'
                )
            );

        $builder->add('shareSupportNeeds', 'choice', array(
                    'choices'=>array(1=>'Yes', 0=>'No'),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'Do you consent to this information being passed on to appropriate parties, such as the course administrator or tutor?'
                )
            )
            ;
        parent::buildForm($builder, $options);
    }

    public function getTemplate(){
        return 'SupportNeeds.html.twig';
    }

    public function prepare(){
        $this->setEntity(SupportNeeds::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request){
        $this->getForm()->bind($request);
        /** @var $entity SupportNeeds */
        $entity = $this->getEntity();

        foreach(array(
                    2=>'additionalNeeds',
                    3=>'additionalNeedsDetail',
                    4=>'firstFloorAccess',
                    5=>'shareSupportNeeds')
                as $order=>$fieldName){
            $getter = 'get'.ucfirst($fieldName);
            $this->getStepProgress()->setFieldValue(
                $fieldName,
                $order,
                $this->getForm()->get($fieldName)->getConfig()->getOption('label'),
                $entity->$getter()
            );
        }

        if($this->getForm()->isValid()){
            $this->setComplete();
        }
        else{
            $this->setComplete(false);
        }
        $this->setUpdated();
        $this->save();
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'validation_groups' => function(FormInterface $form) {
                /** @var $data SupportNeeds */
                $data = $form->getData();
                $groups = array('default');
                if($data->getAdditionalNeeds()){
                    $groups[] = 'has_additional_needs';
                }
                return $groups;
            }
        ));
    }
}