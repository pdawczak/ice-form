<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\MadingleySupportNeeds;

use Ice\JanusClientBundle\Exception\ValidationException;
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


class MadingleySupportNeedsType extends AbstractRegistrationStep{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('additionalNeeds', 'choice', array(
                    'choices'=>array(
                        'Y'=>'Yes',
                        'N'=>'No'
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
                        'Y'=>'I AM able to access the first floor without assistance',
                        'N'=>'I AM NOT able to access the first floor without assistance'
                    ),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'Are you able to access the first floor without assistance?'
                )
            );

        $builder->add('shareSupportNeeds', 'choice', array(
                    'choices'=>array(
                        'Y'=>'Yes',
                        'N'=>'No'
                    ),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'Do you consent to this information being passed on to appropriate parties, such as the course administrator or tutor?'
                )
            )
            ;
        parent::buildForm($builder, $options);
    }

    public function getHtmlTemplate(){
        return 'MadingleySupportNeeds.html.twig';
    }

    public function getJavaScriptTemplate(){
        return 'MadingleySupportNeeds.js.twig';
    }

    public function prepare(){
        $this->setEntity(MadingleySupportNeeds::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null){
        $this->getForm()->bind($request);
        /** @var $entity ResidentialSupportNeeds */
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
                /** @var $data ResidentialSupportNeeds */
                $data = $form->getData();
                $groups = array('default');
                if($data->getAdditionalNeeds() === 'Y'){
                    $groups[] = 'has_additional_needs';
                }
                return $groups;
            }
        ));
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Support needs';
    }
}