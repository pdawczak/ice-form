<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

use Ice\FormBundle\Process\CourseRegistration\EventSubscriber\WeekendAccommodationSubscriber;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Entity\BookingItem;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\VeritasClientBundle\Entity\Course;
use Symfony\Component\Form\FormView;

class WeekendAccommodationType extends AbstractRegistrationStep
{
    protected $childFormOrder = array(
        1 => 'accommodation',
        2 => 'accommodationSharingWith',
        4 => 'adaptedBedroomRequired',
        5 => 'accommodationRequirements',
        6 => 'bedAndBreakfastAccommodation',
        7 => 'platter',
        8 => 'platterOption',
    );

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber(new WeekendAccommodationSubscriber($builder->getFormFactory(), $this->getParentProcess()->getCourse()));

        parent::buildForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'cascade_validation' => true,
        ));
        $resolver->setDefaults(array(
            'validation_groups' => function (FormInterface $form) {
                $groups = [];
                if ($this->isContinueClicked()) {
                    $groups[] = 'Default';
                } else {
                    $groups[] = 'no_validate';
                }
                return $groups;
            }
        ));

        parent::setDefaultOptions($resolver);
    }

    public function processRequest(Request $request = null)
    {
        parent::processRequest($request);
        if ($this->isContinueClicked() && $this->getForm()->isValid()) {
            //Update the booking items

            /** @var WeekendAccommodation $weekendAccommodation */
            $weekendAccommodation = $this->getEntity();

            /** @var Booking $booking */
            $booking = $this->getParentProcess()->getBooking();

            /** @var BookingItem[] $bookingItems */
            $bookingItems = $booking->getBookingItems();

            /** @var Course $course */
            $course = $this->getParentProcess()->getCourse();

            $accommodationSet = false;
            $bAndBSet = false;
            $platterSet = false;

            foreach ($bookingItems as $bookingItem) {
                if ($bookingItem->isCourseAccommodation()) {

                    $courseBookingItem = $this->getParentProcess()->getCourse()->getBookingItemByCode(
                        $weekendAccommodation->getAccommodation()
                    );

                    $bookingItem->setAllByCourseBookingItem($courseBookingItem);
                    $accommodationSet = true;
                } else if ($bookingItem->isAdditionalAccommodation()) {

                    $courseBookingItem = $course->getBookingItemByCode(
                        $weekendAccommodation->getBedAndBreakfastAccommodation()
                    );

                    $bookingItem->setAllByCourseBookingItem($courseBookingItem);
                    $bAndBSet = true;
                } else if ($bookingItem->isEveningPlatter()) {

                    $courseBookingItem = $this->getParentProcess()->getCourse()->getBookingItemByCode(
                        $weekendAccommodation->getPlatter()
                    );

                    $bookingItem->setAllByCourseBookingItem($courseBookingItem);
                    $platterSet = true;
                }
            }

            $booking->setBookingItems($bookingItems);

            if (!$accommodationSet) {
                $booking->addBookingItemByCourseBookingItem(
                    $course->getBookingItemByCode($weekendAccommodation->getAccommodation())
                );
            }

            if (!$bAndBSet) {
                $booking->addBookingItemByCourseBookingItem(
                    $course->getBookingItemByCode($weekendAccommodation->getBedAndBreakfastAccommodation())
                );
            }

            if (!$platterSet) {
                $booking->addBookingItemByCourseBookingItem(
                    $course->getBookingItemByCode($weekendAccommodation->getPlatter())
                );
            }

            $this->getParentProcess()->getMinervaClient()->updateBooking(
                $this->getParentProcess()->getRegistrantId(),
                $this->getParentProcess()->getCourseId(),
                $booking
            );
        }
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Weekend accommodation';
    }

    public function getHtmlTemplate()
    {
        return 'WeekendAccommodation.html.twig';
    }

    public function getJavaScriptTemplate()
    {
        return 'WeekendAccommodation.js.twig';
    }

    public function prepare()
    {
        if ($this->getStepProgress()) {
            $entity = WeekendAccommodation::fromStepProgress($this->getStepProgress());
        } else {
            $entity = new WeekendAccommodation();
        }
        $this->setEntity($entity);
        $this->setPrepared();
    }

    public function processAjaxRequest(Request $request)
    {
        $this->prepare();
        $this->getForm()->bind($request);
        $this->getParentProcess()->setAjaxResponse(new Response($this->renderHtml()));
    }

    public function supportsAjaxResponse()
    {
        return true;
    }

    /**
     * Gets the description for a given field.
     *
     * @param $fieldName
     * @return string
     */
    protected function getFieldDescription($fieldName)
    {
        switch ($fieldName) {
            case 'adaptedBedroomRequired':
                return 'Adapted bedroom required?';
            case 'accommodationRequirements':
                return 'Accommodation Requirements';
        }
        return parent::getFieldDescription($fieldName);
    }

    /**
     * Disable radio buttons for items which are out of stock
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $course = $this->getParentProcess()->getCourse();

        foreach (
            [
                'accommodation' => [ 'unavailableMessage' => 'Out of stock' ],
                'bedAndBreakfastAccommodation' => [ 'unavailableMessage' => 'Out of stock' ],
                'platter' => [ 'unavailableMessage' => 'Out of stock' ]
            ] as $fieldName => $options) {
            if (!isset($view->children[$fieldName])) {
                continue;
            }
            foreach ($view->children[$fieldName]->children as $child) {
                $code = $child->vars['value'];
                if (!$course->getBookingItemByCode($code)->isInStock()) {
                    $child->vars['label'] = $options['unavailableMessage'].' - '.$child->vars['label'];
                    $child->vars['attr']['disabled'] = 'disabled';
                }
            }
        }
    }
}