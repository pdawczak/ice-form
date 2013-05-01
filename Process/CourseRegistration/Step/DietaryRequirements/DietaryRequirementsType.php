<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\DietaryRequirements;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DietaryRequirementsType extends AbstractRegistrationStep{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('dietaryRequirementsListed', 'choice', array(
                'expanded'=>true,
                'multiple'=>true,
                'label'=>'Please indicate if you have any specific dietary requirements.',
                'required'=>false,
                'choices'=>array(
                    'Nut allergy'=>'Allergic to nuts',
                    'Fish allergy'=>'Allergic to fish',
                    'Shellfish allergy'=>'Allergic to shellfish',
                    'Egg allergy'=>'Allergic to eggs',
                    'Wheat allergy'=>'Allergic to wheat',
                    'Milk allergy'=>'Allergic to cows milk',
                    'Vegetarian - no fish'=>'Vegetarian - no fish',
                    'Vegetarian - fish okay'=>'Vegetarian - fish okay',
                    'Vegan'=>'Vegan',
                    'Halaal'=>'Halaal',
                    'Diabetic'=>'Diabetic',
                    'Gluten free'=>'Gluten free',
                    'Other'=>'Other'
                )
            ))
            ->add('dietaryRequirementsSpecific', 'textarea', array(
                'required'=>false,
                'label'=>'If other, please specify'
            ))
        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null){
        $this->getForm()->bind($request);
        /** @var $entity DietaryRequirements */
        $entity = $this->getEntity();

        foreach(array(
                    1=>'dietaryRequirementsListed',
                    2=>'dietaryRequirementsSpecific'
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
        return 'DietaryRequirements.html.twig';
    }

    public function prepare(){
        $this->setEntity(new DietaryRequirements());
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
                /** @var $data DietaryRequirements */
                $data = $form->getData();
                $groups = array('default');
                if(in_array('Other', $data->getDietaryRequirementsListed())){
                    $groups[] = 'selected_other';
                }
                return $groups;
            }
        ));
    }
}