<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

use Ice\FormBundle\Process\CourseRegistration\EventSubscriber\WeekendAccommodationSubscriber;
use Ice\MinervaClientBundle\Entity\Category;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\MinervaClientBundle\Entity\BookingItem;
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

            //Remove all items
            $newBookingItems = [];

            //Add back any items we're not responsible for
            foreach ($bookingItems as $bookingItem) {
                if (
                    !$bookingItem->isCourseAccommodation() &&
                    !$bookingItem->isAdditionalAccommodation() &&
                    !$bookingItem->isEveningPlatter()
                ) {
                    $newBookingItems[] = $bookingItem;
                }
            }

            $booking->setBookingItems($newBookingItems);

            //Add back any items which we are responsible for
            if ($weekendAccommodation->getAccommodation()) {
                $booking->addBookingItemByCourseBookingItem(
                    $course->getBookingItemByCode($weekendAccommodation->getAccommodation())
                );

                if ($weekendAccommodation->getBedAndBreakfastAccommodation()) {
                    $booking->addBookingItemByCourseBookingItem(
                        $course->getBookingItemByCode(
                            $weekendAccommodation->getBedAndBreakfastAccommodation()
                        )
                    );

                    if ($weekendAccommodation->getPlatter()) {
                        $booking->addBookingItemByCourseBookingItem(
                            $course->getBookingItemByCode(
                                $weekendAccommodation->getPlatter()
                            )
                        );
                    }
                }
            }

            //Persist the new items
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
                'accommodation' => ['unavailableMessage' => 'Unavailable'],
                'bedAndBreakfastAccommodation' => ['unavailableMessage' => 'Unavailable'],
                'platter' => ['unavailableMessage' => 'Unavailable']
            ] as $fieldName => $options) {
            if (!isset($view->children[$fieldName])) {
                continue;
            }
            foreach ($view->children[$fieldName]->children as $child) {
                $code = $child->vars['value'];
                if ($courseItem = $course->getBookingItemByCode($code)) {
                    if ($courseItem->getPrice()) {
                        $child->vars['label'].= sprintf(" £%.02f", $courseItem->getPrice()/100);
                    }
                    if (!$course->getBookingItemByCode($code)->isInStock()) {
                        $child->vars['label'] = $options['unavailableMessage'] . ' - ' . $child->vars['label'];
                        $child->vars['attr']['disabled'] = 'disabled';
                    }
                }
            }
        }
    }

    /**
     * If an item we're responsible for becomes invalid, mark the step incomplete and return true
     *
     * @param BookingItem $item
     * @return bool
     */
    public function invalidateBookingItem(BookingItem $item)
    {
        if (
            $item->isCourseAccommodation() ||
            $item->isEveningPlatter() ||
            $item->isAdditionalAccommodation()
        ) {
            if ($this->isComplete()) {
                $this->setComplete(false);
                $this->save();
            }
            return true;
        }
        return parent::invalidateBookingItem($item);
    }
}