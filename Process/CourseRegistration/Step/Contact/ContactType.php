<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\Contact;

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

class ContactType extends AbstractRegistrationStep{
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
     * @param Request $request
     */
    public function processRequest(Request $request){
        $this->getForm()->bind($request);
        if($this->getForm()->isValid()){
            /** @var $entity Contact */
            $entity = $this->getEntity();
            $this->getStepProgress()->setFieldValue('address1', 1, 'Address line 1', $entity->getAddress1())
                ->setFieldValue('address2', 2, 'Address line 2', $entity->getAddress2())
                ->setFieldValue('address3', 3, 'Address line 3', $entity->getAddress3())
                ->setFieldValue('address4', 4, 'Address line 4', $entity->getAddress4())
                ->setFieldValue('city',     5, 'City', $entity->getCity())
                ->setFieldValue('postCode', 6, 'Post code', $entity->getPostCode())
                ->setFieldValue('country',  7, 'Country', $entity->getCountry())
                ->setFieldValue('telephone', 8, 'Telephone', $entity->getTelephone())
                ;
            $this->setComplete();
            $this->save();
        }
    }

    public function prepare(){
        $contact = Contact::fromStepProgress($this->getStepProgress());
        $this->setEntity($contact);
        $this->setPrepared();
    }

    public function getTemplate(){
        return 'Contact.html.twig';
    }
}