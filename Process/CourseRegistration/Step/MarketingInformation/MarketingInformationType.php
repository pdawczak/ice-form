<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ice\JanusClientBundle\Entity\User;

class MarketingInformationType extends AbstractRegistrationStep{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('marketingHowHeard', 'choice', array(
                'expanded'=>true,
                'multiple'=>true,
                'label'=>'How did you hear about this course? Please select all that apply.',
                'choices'=>array(
                    'Advert'=>'Advert',
                    'Attended previous course(s)'=>'Attended previous course(s)',
                    'Course brochure'=>'Course brochure',
                    'Event'=>'Event',
                    'Flyer/leaflet'=>'Flyer/leaflet',
                    'Institute website'=>'Institute website (www.ice.cam.ac.uk)',
                    'Newspaper or magazine article'=>'Newspaper or magazine article',
                    'Personal recommendation'=>'Personal recommendation',
                    'Other'=>'Other',
                )
            ))
            ->add('marketingDetail', 'textarea', array(
                'required'=>false,
                'label'=>'Please give details of how/where you found our advert, article, brochure, event, flyer or website, as applicable.'
            ))
            ->add('marketingOptIn', 'checkbox', array(
                'label'=>'Please tick if you would like to receive occasional emails about upcoming courses, events and
                    other activities at the Institute',
                'required'=>false
                )
            )
        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null){
        $this->getForm()->bind($request);
        /** @var $entity MarketingInformation */
        $entity = $this->getEntity();

        foreach(array(
                    1=>'marketingHowHeard',
                    2=>'marketingDetail',
                    3=>'marketingOptIn'
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

        $this->getStepProgress()->setFieldValue(
            'frontEndBooking',
            4,
            'Is this a front end booking?',
            $this->getParentProcess()->isAdministrator()?'No':'Yes'
        );

        if($this->getForm()->isValid()){
            $this->setComplete();
        }
        else{
            $this->setComplete(false);
        }
        $this->setUpdated();
        $this->save();
    }

    public function getHtmlTemplate(){
        return 'MarketingInformation.html.twig';
    }

    public function prepare(){
        $this->setEntity(MarketingInformation::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
    }
}