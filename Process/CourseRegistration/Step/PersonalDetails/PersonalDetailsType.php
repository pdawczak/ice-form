<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\PersonalDetails;

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

class PersonalDetailsType extends AbstractRegistrationStep{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $subscriber = new EventListener\PreviousStudyFieldSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);

        if($this->getParentProcess()->getRegistrantId()){
            $builder
                ->add('fullName', 'display', array('label'=>'Name',
                    'data'=>$this->getEntity()->getFullName(), 'mapped'=>false)
                )
                ->add('email', 'display', array('label'=>'Email address'))
            ;

            if(null === ($dob = $this->getParentProcess()->getRegistrant()->getDob())){
                $builder->add('dob', 'birthday', array(
                    'label'=>'Date of birth'
                ));
            }
            else{
                $builder->add('dob', 'display', array(
                    'label'=>'Date of birth',
                    'data'=>$dob->format('d/m/Y'),
                    'mapped'=>false))
                ;
            }
        }
        else{
            $builder
                ->add('title', 'choice', array(
                    'label'=>'Title',
                    'multiple'=>false,
                    'expanded'=>false,
                    'choices'=>array(
                        'Mr'=>'Mr',
                        'Mrs'=>'Mrs',
                        'Miss'=>'Miss',
                        'Ms'=>'Ms',
                        'Dr'=>'Dr',
                        'Prof'=>'Prof',
                        'Revd'=>'Revd',
                        'Misc'=>'Misc',
                        'Mx'=>'Mx',
                    )
                ))
                ->add('firstNames', 'text', array(
                    'label'=>'First name'
                ))
                ->add('middleNames', 'text', array(
                    'label'=>'Middle name(s)',
                    'required'=>false
                ))
                ->add('lastNames', 'text', array(
                    'label'=>'Last name'
                ))
                ->add('email', 'email', array(
                    'label'=>'Email address'
                ))
                ->add('dob', 'birthday', array(
                    'label'=>'Date of birth'
                ))
                ->add('plainPassword', 'repeated', array(
                    'type'=>'password',
                    'invalid_message' => 'The password fields must match',
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password')
                ));
            ;
        }


        $builder->add('gender', 'choice', array(
            'label'=>'Sex',
            'multiple'=>false,
            'expanded'=>true,
            'choices'=>array(
                'm'=>'Male',
                'f'=>'Female'
            )
        ));
        parent::buildForm($builder, $options);
    }

    /**
     * @param string $fieldName
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getErrorTargetByFieldName($fieldName){
        try{
            switch($fieldName){
                case null:
                    $target = $this->getForm();
                    break;
                case 'plainPassword':
                    $target = $this->getForm()->get($fieldName)->get('first');
                    break;
                default:
                    $target = $this->getForm()->get($fieldName);
                    break;
            }
        }
        catch(\OutOfBoundsException $e2){
            //Field doesn't exist. Add this error to the root so the user can see the message
            $target = $target = $this->getForm();
        }
        return $target;
    }

    /**
     * @param FormInterface|string $field
     * @param array $fieldErrors
     */
    private function addFieldErrors($field, $fieldErrors){
        if(!$field instanceof FormInterface){
            $field = $this->getErrorTargetByFieldName($field);
        }
        if($field){
            foreach($fieldErrors as $fieldError){
                if(stripos($fieldError, 'email')!==false){
                    $this->getErrorTargetByFieldName('email')
                        ->addError(new FormError($fieldError));
                }
                else{
                    $field->addError(new FormError($fieldError));
                }
            }
        }
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request){


        $this->getForm()->bind($request);

        if($this->getForm()->isValid()){
            /** @var $data PersonalDetails */
            $data = $this->getForm()->getData();
            //No user, now we can create one!
            if(!$this->getParentProcess()->getRegistrantId()){
                /** @var $newUser User */
                try{
                    $newUser = $this->getParentProcess()->getJanusClient()->createUser(
                        array(
                            'title'=>$data->getTitle(),
                            'firstNames'=>$data->getFirstNames(),
                            'middleNames'=>$data->getMiddleNames(),
                            'lastNames'=>$data->getLastNames(),
                            'plainPassword'=>$data->getPlainPassword(),
                            'email'=>$data->getEmail(),
                            'dob'=>$data->getDob()->format('Y-m-d'),
                        )
                    );

                    if($newUser && $newUser->getId()){
                        $this->getParentProcess()->setRegistrantId($newUser->getUsername());
                    }

                    $progress = $this->getParentProcess()->getProgress(true);
                    foreach($progress->getStepProgresses() as $stepProgress){
                        if($stepProgress->getStepName()===$this->getReference()){
                            $this->setStepProgress($stepProgress);
                            break;
                        }
                    }

                    $this->setComplete();
                    /** @var $entity PersonalDetails */
                    $entity = $this->getEntity();
                    $this->getStepProgress()->setFieldValue('title', 1, 'Title', $entity->getTitle());
                    $this->save();
                }
                catch(ValidationException $e){
                    /** @var $form \Ice\JanusClientBundle\Response\FormError */
                    $form = $e->getForm();

                    $errors = $form->getErrorsAsAssociativeArray(true);
                    foreach($errors as $field => $fieldErrors){
                        $this->addFieldErrors($field, $fieldErrors);
                    }
                    $this->getForm()->isValid();
                }
            }

            if($user = $this->getParentProcess()->getRegistrant()){
                $booking = $this->getParentProcess()->getBooking(true);
                $this->setComplete();
                $this->save();
            }
        }

    }

    public function getTemplate(){
        return 'PersonalDetails.html.twig';
    }


    /**
     * Sets the entity
     */
    public function prepare(){
        if($user = $this->getParentProcess()->getRegistrant()){
            $entity = PersonalDetails::fromUser($user);
        }
        else{
            $entity = new PersonalDetails();
        }
        $this->setEntity($entity);
        $this->setPrepared();
    }

    /**
     * @return bool
     */
    public function isAvailable(){
        return true;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'validation_groups' => function(FormInterface $form) {
                /** @var $data PersonalDetails */
                $data = $form->getData();
                $groups = array('default');
                if(!$data->getRegistrantId()){
                    $groups[] = 'new_user';
                }
                return $groups;
            }
        ));
    }
}