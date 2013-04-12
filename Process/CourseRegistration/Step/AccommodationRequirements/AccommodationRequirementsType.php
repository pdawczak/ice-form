<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\AccommodationRequirements;

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

class AccommodationRequirementsType extends AbstractRegistrationStep{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder

        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request){
        $this->getForm()->bind($request);
        /** @var $entity AccommodationRequirements */
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
        return 'AccommodationRequirements.html.twig';
    }

    public function prepare(){
        $this->setEntity(new AccommodationRequirements());
        $this->setPrepared();
    }
}