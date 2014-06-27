<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\PersonalDetails;

use Ice\JanusClientBundle\Exception\ValidationException;
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

class PersonalDetailsType extends AbstractRegistrationStep
{
    /**
     * {@inheritDoc}
     */
    protected $childFormOrder = array(
        6 => 'dob',
        7 => 'sex',
        8 => 'previousContact',
        9 => 'previousTitle',
        10 => 'previousFirstName',
        11 => 'previousMiddleName',
        12 => 'previousLastName',
        13 => 'crsId',
    );

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new EventListener\PreviousStudyFieldSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);

        if ($this->getParentProcess()->getRegistrantId()) {
            $builder
                ->add('fullName', 'display', array('label' => 'Name',
                        'data' => $this->getEntity()->getFullName(), 'mapped' => false)
                );

            if (!$email = $this->getParentProcess()->getRegistrant()->getEmail()) {
                $builder->add('email', 'email', array(
                    'label' => 'Email address'
                ));
            } else {
                $builder->add('email', 'display', array(
                    'label' => 'Email address',
                    'data' => $email,
                    'mapped' => false,
                ));
            }

            if (null === ($dob = $this->getParentProcess()->getRegistrant()->getDob())) {
                $builder->add('dob', 'birthday', array(
                    'label' => 'Date of birth',
                    'input' => "datetime",
                    'widget' => "choice",
                    'format' => 'd MMM yyyy',
                    'empty_value' => array(
                        'day' => 'Day',
                        'month' => 'Month',
                        'year' => 'Year',
                    ),
                ));
            } else {
                $builder->add('dob', 'display', array(
                    'label' => 'Date of birth',
                    'data' => $dob->format('d/m/Y'),
                    'mapped' => false));
            }

            if (null === ($sex = $this->getParentProcess()->getRegistrant()->getAttributeValueByName('sex',null))) {
                $builder->add('sex', 'choice', array(
                    'label' => 'Sex',
                    'multiple' => false,
                    'expanded' => true,
                    'choices' => array(
                        'm' => 'Male',
                        'f' => 'Female'
                    ),
                ));
            } else {
                $builder->add('sex', 'display', array(
                    'label' => 'Sex',
                    'data' => $sex == 'm' ? 'Male' : 'Female',
                    'mapped' => false));
            }
        } else {
            $builder
                ->add('title', 'choice', array(
                    'label' => 'Title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'choices' => array(
                        '' => 'Please select',
                        'Dr' => 'Dr',
                        'Misc' => 'Misc',
                        'Miss' => 'Miss',
                        'Mr' => 'Mr',
                        'Mrs' => 'Mrs',
                        'Ms' => 'Ms',
                        'Mx' => 'Mx',
                        'Prof' => 'Prof',
                        'Revd' => 'Revd',
                    )
                ))
                ->add('firstNames', 'text', array(
                    'label' => 'First name'
                ))
                ->add('middleNames', 'text', array(
                    'label' => 'Middle name(s)',
                    'required' => false
                ))
                ->add('lastNames', 'text', array(
                    'label' => 'Last name'
                ))
                ->add('email', 'email', array(
                    'label' => 'Email address'
                ))
                ->add('dob', 'birthday', array(
                    'label' => 'Date of birth',
                    'input' => "datetime",
                    'widget' => "choice",
                    'format' => 'd MMM yyyy',
                    'empty_value' => array(
                        'day' => 'Day',
                        'month' => 'Month',
                        'year' => 'Year',
                    ),
                ))
                ->add('sex', 'choice', array(
                    'label' => 'Sex',
                    'multiple' => false,
                    'expanded' => true,
                    'required' => false,
                    'choices' => array(
                        'm' => 'Male',
                        'f' => 'Female'
                    ),
                ))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match',
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password')
                ));
        }


        $builder
            ->add('previousContact', 'choice', array(
                'label' => 'Have you previously contacted (even for an enquiry), applied or studied with the University of Cambridge and/or the Institute of Continuing Education (ICE)?',
                'choices' => array(
                    'Y' => 'Yes',
                    'N' => 'No',
                ),
                'data' => 'N',
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('previousTitle', 'choice', array(
                'label' => 'Previous title',
                'multiple' => false,
                'expanded' => false,
                'choices' => array(
                    'Mr' => 'Mr',
                    'Mrs' => 'Mrs',
                    'Miss' => 'Miss',
                    'Ms' => 'Ms',
                    'Dr' => 'Dr',
                    'Prof' => 'Prof',
                    'Revd' => 'Revd',
                    'Misc' => 'Misc',
                    'Mx' => 'Mx',
                ),
                'required' => false,
            ))
            ->add('previousFirstName', 'text', array(
                'label' => 'Previous first name',
                'required' => false,
            ))
            ->add('previousMiddleName', 'text', array(
                'label' => 'Previous middle name',
                'required' => false
            ))
            ->add('previousLastName', 'text', array(
                'label' => 'Previous last name',
                'required' => false,
            ))
            ->add('crsId', 'text', array(
                'label' => 'If you have an existing CRSid (student identifier comprising your initials and numbers, e.g. jb101) please enter it here',
                'required' => false,
            ));
        parent::buildForm($builder, $options);
    }

    /**
     * @param string $fieldName
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getErrorTargetByFieldName($fieldName)
    {
        try {
            switch ($fieldName) {
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
        } catch (\OutOfBoundsException $e2) {
            //Field doesn't exist. Add this error to the root so the user can see the message
            $target = $this->getForm();
        }
        return $target;
    }

    /**
     * @param FormInterface|string $field
     * @param array $fieldErrors
     */
    private function addFieldErrors($field, $fieldErrors)
    {
        if (!$field instanceof FormInterface) {
            $field = $this->getErrorTargetByFieldName($field);
        }
        if ($field) {
            foreach ($fieldErrors as $fieldError) {
                if (stripos($fieldError, 'email') !== false) {
                    $this->getErrorTargetByFieldName('email')
                        ->addError(new FormError($fieldError));
                } else {
                    $field->addError(new FormError($fieldError));
                }
            }
        }
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null)
    {
        $this->getForm()->bind($request);

        /** @var $data PersonalDetails */
        $data = $this->getForm()->getData();

        if ($this->getForm()->isValid()) {
            //No user, now we can create one!
            if (!$this->getParentProcess()->getRegistrantId()) {
                /** @var $newUser User */
                try {
                    $newUser = $this->getParentProcess()->getJanusClient()->createUser(
                        array(
                            'title' => $data->getTitle(),
                            'firstNames' => $data->getFirstNames(),
                            'middleNames' => $data->getMiddleNames(),
                            'lastNames' => $data->getLastNames(),
                            'plainPassword' => $data->getPlainPassword(),
                            'email' => $data->getEmail(),
                            'dob' => $data->getDob()->format('Y-m-d'),
                        )
                    );

                    if ($newUser && $newUser->getId()) {
                        $this->getParentProcess()->setRegistrantId($newUser->getUsername());
                    }

                    //Set personal attributes against the user
                    $this->getParentProcess()->getJanusClient()->setAttributes(
                        $newUser->getUsername(),
                        $newUser->getUsername(),
                        [
                            'sex'=>$data->getSex()
                        ]
                    );

                    $progress = $this->getParentProcess()->getProgress(true);
                    foreach ($progress->getStepProgresses() as $stepProgress) {
                        if ($stepProgress->getStepName() === $this->getReference()) {
                            $this->setStepProgress($stepProgress);
                            break;
                        }
                    }
                } catch (ValidationException $e) {
                    /** @var $form \Ice\JanusClientBundle\Response\FormError */
                    $form = $e->getForm();

                    $errors = $form->getErrorsAsAssociativeArray(true);
                    foreach ($errors as $field => $fieldErrors) {
                        $this->addFieldErrors($field, $fieldErrors);
                    }
                    $this->getForm()->isValid();
                }
            } else {
                // Existing user
                // Set DOB and/or email address if this has not been set before
                $existingUser = $this->getParentProcess()->getRegistrant();
                if (!$existingUser->getDob() || !$existingUser->getEmail()) {
                    try {
                        $this->getParentProcess()->getJanusClient()->updateUser($existingUser->getUsername(), array(
                            'title' => $data->getTitle(),
                            'firstNames' => $data->getFirstNames(),
                            'middleNames' => $data->getMiddleNames(),
                            'lastNames' => $data->getLastNames(),
                            'email' => $data->getEmail(),
                            'dob' => $data->getDob() ? $data->getDob()->format('Y-m-d') : null,
                        ));
                    } catch (ValidationException $e) {
                        /** @var $form \Ice\JanusClientBundle\Response\FormError */
                        $form = $e->getForm();

                        $errors = $form->getErrorsAsAssociativeArray(true);
                        foreach ($errors as $field => $fieldErrors) {
                            $this->addFieldErrors($field, $fieldErrors);
                        }
                        $this->getForm()->isValid();
                    }
                }

                //Set personal attributes against the user
                $this->getParentProcess()->getJanusClient()->setAttributes(
                    $existingUser->getUsername(),
                    $existingUser->getUsername(),
                    [
                        'sex'=>$data->getSex()
                    ]
                );
            }
        }


        //If still valid after any Janus side validation
        if ($this->getForm()->isValid()) {
            parent::processRequest();

            $this->getStepProgress()->setFieldValue('title', 1, 'Title', $data->getTitle());
            $this->getStepProgress()->setFieldValue('firstNames', 2, 'First name(s)', $data->getFirstNames());
            $this->getStepProgress()->setFieldValue('middleNames', 3, 'Middle name(s)', $data->getMiddleNames());
            $this->getStepProgress()->setFieldValue('lastNames', 4, 'Last name(s)', $data->getLastNames());
            $this->getStepProgress()->setFieldValue('email', 5, 'Email address', $data->getEmail());

            $this->setComplete();
            $this->save();
        }
    }

    public function getHtmlTemplate()
    {
        return 'PersonalDetails.html.twig';
    }


    public function getJavaScriptTemplate()
    {
        return 'PersonalDetails.js.twig';
    }


    /**
     * Sets the entity
     */
    public function prepare()
    {
        if (!$user = $this->getParentProcess()->getRegistrant()) {
            $user = new User;
        }

        $entity = PersonalDetails::fromUserAndStepProgress($user, $this->getStepProgress());

        $this->setEntity($entity);
        $this->setPrepared();
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return true;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'validation_groups' => function (FormInterface $form) {
                /** @var $data PersonalDetails */
                $data = $form->getData();
                $groups = array('default');
                if (!$data->getRegistrantId()) {
                    $groups[] = 'new_user';
                }
                else {
                    if (null === $data->getDob()) {
                        $groups[] = 'require_dob';
                    }
                    if (null === $data->getSex()) {
                        $groups[] = 'require_sex';
                    }
                }
                return $groups;
            }
        ));
    }
}