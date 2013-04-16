<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

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

class WeekendAccommodationType extends AbstractRegistrationStep{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('accommodationChoice', 'choice', array(
            'multiple'=>false,
            'expanded'=>true,
            'required'=>false,
            'label'=>'Your accommodation selection',
            'choices'=>array(
                'none'=>'No accommodation',
                'single'=>'Accommodation at Madingley Hall - single',
                'double'=>'Accommodation at Madingley Hall - double',
                'twin'=>'Accommodation at Madingley Hall - twin',
            )

        ))

        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @return string
     */
    public function getTitle(){
        return 'Accommodation';
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request){
        $this->getForm()->bind($request);
        /** @var $entity WeekendAccommodation */
        $entity = $this->getEntity();

        foreach(array(
                )
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

    public function getTemplate(){
        return 'WeekendAccommodation.html.twig';
    }

    public function prepare(){
        $this->setEntity(new WeekendAccommodation());
        $this->setPrepared();
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'validation_groups' => function(FormInterface $form) {
                /** @var $data WeekendAccommodation */
                $data = $form->getData();
                $groups = array('default');
                return $groups;
            }
        ));
    }
}