<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation;

use Ice\FormBundle\Process\CourseRegistration;
use Ice\MinervaClientBundle\Entity\Booking;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ice\JanusClientBundle\Entity\User;
use Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation\BookingCodeHandler\BookingCodeHandlerManager;

class MarketingInformationType extends AbstractRegistrationStep
{
    private $bookingCodeHandlerManager;

    public function __construct(CourseRegistration $parentProcess, $reference = null, BookingCodeHandlerManager $manager)
    {
        $this->bookingCodeHandlerManager = $manager;
        parent::__construct($parentProcess, $reference);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){

        $marketingOptIn = false;
        if ($this->getParentProcess()->getRegistrantId()) {
            $marketingOptIn = $this->getParentProcess()->getRegistrant()->getAttributeByName('marketingOptIn');
            $marketingOptIn = $marketingOptIn ? (bool)$marketingOptIn->getValue() : false;
        }

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
            ));

        $builder->add('marketingOptIn', 'checkbox', array(
                'label'=>'Please tick if you would like to receive occasional emails about upcoming courses, events and
                    other activities at the Institute',
                'required'=>false,
                'data' => $marketingOptIn
            )
        );

        $builder->add('bookingCode', 'text', array(
                    'label'=>'If you have a booking code or Friends of Madingley Hall number, please enter it here',
                    'required'=>false,
                    'constraints'=>[
                        new BookingCodeConstraint([
                            'course'=>$this->getParentProcess()->getCourse(),
                            'user'=>$this->getParentProcess()->getRegistrant()
                        ])
                    ]
                )
            )
            ->addEventListener(FormEvents::PRE_BIND, function (FormEvent $e) {
                $data = $e->getData();
                if (isset($data['bookingCode'])) {
                    $data['bookingCode'] = strtoupper($data['bookingCode']);
                    $e->setData($data);
                }
            })
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
                    3=>'marketingOptIn',
                    4=>'bookingCode'
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

            $this->persistRegistrantAttributes(['marketingOptIn']);

            $course = $this->getParentProcess()->getCourse();
            $user = $this->getParentProcess()->getRegistrant();
            $booking = $this->getParentProcess()->getBooking();

            $bookingCodeHandler = $this->bookingCodeHandlerManager->getHandlerFor(
                $entity->getBookingCode(),
                $course,
                $user
            );

            if ($bookingCodeHandler && $bookingCodeHandler->updateBooking(
                $entity->getBookingCode(),
                $booking,
                $course,
                $user
            )) {
                $this->getParentProcess()->persistBooking();
            }

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

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function isAvailable()
    {
        //This step is only available if no orders have been placed, because it requires an order amendment which we can't
        //deal with yet.
        return $this->areRegistrantAndCourseKnown() &&
        $this->getParentProcess()->getBooking(false) &&
        !$this->getParentProcess()->getBooking(false)->getOrderReference();
    }
}
