<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\AttendeeDetails;

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

class AttendeeDetailsType extends AbstractRegistrationStep{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('address1', 'text', array('label'=>'Address line 1', 'required'=>true, 'constraints'=>array(new NotBlank())))
            ->add('address2', 'text', array('label'=>'Address line 2', 'required'=>false))
            ->add('address3', 'text', array('label'=>'Address line 3', 'required'=>false))
            ->add('address4', 'text', array('label'=>'Address line 4', 'required'=>false))
            ->add('city', 'text', array('label'=>'City', 'required'=>true, 'constraints'=>array(new NotBlank())))
            ->add('postCode', 'text', array('label'=>'Post code', 'required'=>true, 'constraints'=>array(new NotBlank())))
            ->add('country', 'country', array('label'=>'Country', 'required'=>true, 'constraints'=>array(new NotBlank())))
            ->add('telephone', 'text', array('label'=>'Telephone', 'required'=>true, 'constraints'=>array(new NotBlank())))
        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @return string
     */
    public function getTitle(){
        return 'Contact';
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request){
        $this->getForm()->bind($request);
        /** @var $entity AttendeeDetails */
        $entity = $this->getEntity();

        foreach(array(
                    1=>'address1',
                    2=>'address2',
                    3=>'address3',
                    4=>'address4',
                    5=>'city',
                    6=>'postCode',
                    7=>'country',
                    8=>'telephone',
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

    /**
     * @return mixed|void
     */
    public function prepare(){
        $contact = AttendeeDetails::fromStepProgress($this->getStepProgress());
        $this->setEntity($contact);
        $this->setPrepared();
    }

    public function getTemplate(){
        return 'AttendeeDetails.html.twig';
    }
}