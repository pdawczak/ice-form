<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\AttendeeDetails;

use Ice\JanusClientBundle\Exception\ValidationException;
use JMS\Serializer\Tests\Fixtures\Person;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ice\JanusClientBundle\Entity\User;

class AttendeeDetailsType extends AbstractRegistrationStep
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address1', 'text', array('label' => 'Address line 1', 'required' => true, 'constraints' => array(new NotBlank(['groups' => ['Default', 'TermTimeAddressRequired']]))))
            ->add('address2', 'text', array('label' => 'Address line 2', 'required' => false))
            ->add('address3', 'text', array('label' => 'Address line 3', 'required' => false))
            ->add('town', 'text', array('label' => 'Town', 'required' => true, 'constraints' => array(new NotBlank(['groups' => ['Default', 'TermTimeAddressRequired']]))))
            ->add('county', 'text', array('label' => 'County', 'required' => false))
            ->add('postCode', 'text', array('label' => 'Post code', 'required' => true, 'constraints' => array(new NotBlank(['groups' => ['Default', 'TermTimeAddressRequired']]))))
            ->add('country', 'country', array('label' => 'Country', 'required' => true, 'constraints' => array(new NotBlank(['groups' => ['Default', 'TermTimeAddressRequired']]))))
            ->add('telephone', 'text', array('label' => 'Telephone', 'required' => true, 'constraints' => array(new NotBlank(['groups' => ['Default', 'TermTimeAddressRequired']]))));

        if ($this->enableTermTimeAddressQuestion()) {
            $builder->add('termTimeAddressTheSame', 'checkbox', array(
                'required' => false,
                'label' => 'Is term time address as above?'
            ));

            $builder
                ->add('termTimeAddress1', 'text', array('label' => 'Address line 1', 'required' => false, 'constraints' => array(new NotBlank(['groups' => ['TermTimeAddressRequired']]))))
                ->add('termTimeAddress2', 'text', array('label' => 'Address line 2', 'required' => false))
                ->add('termTimeAddress3', 'text', array('label' => 'Address line 3', 'required' => false))
                ->add('termTimeTown', 'text', array('label' => 'Town', 'required' => false, 'constraints' => array(new NotBlank(['groups' => ['TermTimeAddressRequired']]))))
                ->add('termTimeCounty', 'text', array('label' => 'County', 'required' => false))
                ->add('termTimePostCode', 'text', array('label' => 'Post code', 'required' => false, 'constraints' => array(new NotBlank(['groups' => ['TermTimeAddressRequired']]))))
                ->add('termTimeCountry', 'country', array('label' => 'Country', 'required' => false, 'constraints' => array(new NotBlank(['groups' => ['TermTimeAddressRequired']]))))
                ->add('termTimeTelephone', 'text', array('label' => 'Telephone', 'required' => false, 'constraints' => array(new NotBlank(['groups' => ['TermTimeAddressRequired']]))));
        }
        parent::buildForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => function(FormInterface $form) {
                $data = $form->getData();
                $groups = ['Default'];
                if (! $data->getTermTimeAddressTheSame()) {
                    $groups[] = 'TermTimeAddressRequired';
                }
                return $groups;
            },
        ));
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Contact';
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null)
    {
        $this->getForm()->bind($request);
        /** @var $entity AttendeeDetails */
        $entity = $this->getEntity();

        $attributes = [];

        $fields = array(
            1 => 'address1',
            2 => 'address2',
            3 => 'address3',
            4 => 'town',
            5 => 'county',
            6 => 'postCode',
            7 => 'country',
            8 => 'telephone',
        );

        if ($this->enableTermTimeAddressQuestion()) {
            $fields[9]  = 'termTimeAddressTheSame';
            $fields[10] = 'termTimeAddress1';
            $fields[11] = 'termTimeAddress2';
            $fields[12] = 'termTimeAddress3';
            $fields[13] = 'termTimeTown';
            $fields[14] = 'termTimeCounty';
            $fields[15] = 'termTimePostCode';
            $fields[16] = 'termTimeCountry';
            $fields[17] = 'termTimeTelephone';
        }

        foreach ( $fields
                 as $order => $fieldName) {
            $getter = 'get' . ucfirst($fieldName);
            $value = $entity->$getter();

            $this->getStepProgress()->setFieldValue(
                $fieldName,
                $order,
                $this->getForm()->get($fieldName)->getConfig()->getOption('label'),
                $value
            );

            if (!$value) {
                $value = '';
            }

            $attributes[$fieldName] = $value;
        }

        $iceId = $this->getParentProcess()->getRegistrantId();

        if ($this->getForm()->isValid()) {
            $this->setComplete();
            if (array_keys($attributes, 'termTimeAddressTheSame')) {
                $attributes['termTimeAddressTheSame'] = $attributes['termTimeAddressTheSame'] ? 'T' : 'F';
            }
            $this->getParentProcess()->getJanusClient()->setAttributes($iceId, $iceId, $attributes);
        } else {
            $this->setComplete(false);
        }
        $this->setUpdated();
        $this->save();
    }

    /**
     * @return mixed|void
     */
    public function prepare()
    {
        if ($this->getStepProgress()->getUpdated()) {
            $contact = AttendeeDetails::fromStepProgress($this->getStepProgress());
        } else {
            $contact = AttendeeDetails::fromUser($this->getParentProcess()->getRegistrant());
        }
        $this->setEntity($contact);
        $this->setPrepared();
    }

    public function getHtmlTemplate()
    {
        return 'AttendeeDetails.html.twig';
    }

    /**
     * @return string
     */
    public function getJavaScriptTemplate()
    {
        return 'AttendeeDetails.js.twig';
    }

    private function enableTermTimeAddressQuestion()
    {
        return $this->version > 1;
    }
}
