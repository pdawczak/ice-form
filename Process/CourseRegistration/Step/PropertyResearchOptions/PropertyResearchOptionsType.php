<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\PropertyResearchOptions;


use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\MinervaClientBundle\Entity\BookingItem;
use Ice\VeritasClientBundle\Entity\Course;
use Symfony\Component\Validator\Constraints\NotBlank;

class PropertyResearchOptionsType extends AbstractRegistrationStep
{

    protected $childFormOrder = [
        1 => 'propertyResearchDiscount',
        2 => 'accommodationBefore'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $this->getParentProcess()->getCourse();
        $discountOptions = [
            ['NONE' => 'I am not eligible for a discount, or none is available']
        ];
        $accomOptions = [
            ['NONE' => 'No additional accommodation']
        ];
        foreach ($course->getBookingItems() as $items) {
            if (substr($items->getCode(), 0, 12)==='DISCOUNT-SPR') {
                $discountOptions[$items->getCode()] = $items->getTitle();
            }
            if (substr($items->getCode(), 0, 15)==='ACCOM-SPECIAL-A') {
                $accomOptions[$items->getCode()] = $items->getTitle();
            }
        }
        $builder
            ->add('propertyResearchDiscount', 'choice', [
                'label' => 'Discount',
                'expanded' => true,
                'choices' => $discountOptions,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('accommodationBefore', 'choice', [
                'label' => 'Additional accommodation required',
                'expanded' => true,
                'choices' => $accomOptions,
                'constraints' => [
                    new NotBlank()
                ]
            ])
        ;
        parent::buildForm($builder, $options);
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
                'propertyResearchDiscount' => ['unavailableMessage' => 'Unavailable'],
                'accommodationBefore' => ['unavailableMessage' => 'Unavailable']
            ] as $fieldName => $options) {
            if (!isset($view->children[$fieldName])) {
                continue;
            }
            foreach ($view->children[$fieldName]->children as $child) {
                $code = $child->vars['value'];
                if ($courseItem = $course->getBookingItemByCode($code)) {
                    if (!$course->getBookingItemByCode($code)->isInStock()) {
                        $child->vars['label'] = $options['unavailableMessage'] . ' - ' . $child->vars['label'];
                        $child->vars['attr']['disabled'] = 'disabled';
                    }
                }
            }
        }
    }

    public function getTitle()
    {
        return 'Course options';
    }

    public function getHtmlTemplate()
    {
        return 'PropertyResearchOptions.html.twig';
    }

    public function renderHtml(array $vars = array())
    {
        $vars['courseFee'] = $this->getParentProcess()->getCourse()->getTuitionFee();
        return parent::renderHtml($vars);
    }

    /**
     * Sets up entities, pre-populates fields
     *
     * @return mixed
     */
    public function prepare()
    {
        if ($this->getStepProgress()->getUpdated()) {
            $contact = PropertyResearchOptions::fromStepProgress($this->getStepProgress());
        } else {
            $contact = new PropertyResearchOptions();
        }
        $this->setEntity($contact);
        $this->setPrepared();
    }

    public function processRequest(Request $request = null)
    {
        if ($request) {
            $this->getForm()->bind($request);
        }

        $this->setStepProgressValues(
            $this->getEntity(),
            $this->childFormOrder,
            $this->getForm(),
            $this->getStepProgress()
        );

        if ($this->isContinueClicked() && $this->getForm()->isValid()) {

            /** @var PropertyResearchOptions $data */
            $data = $this->getForm()->getData();

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
                    substr($bookingItem->getCode(), 0, 12) !== 'DISCOUNT-SPR' &&
                    substr($bookingItem->getCode(), 0, 15) !== 'ACCOM-SPECIAL-A'
                ) {
                    $newBookingItems[] = $bookingItem;
                }
            }

            $booking->setBookingItems($newBookingItems);

            //Add back any items which we are responsible for
            if ($data->getPropertyResearchDiscount() !== 'NONE') {
                $booking->addBookingItemByCourseBookingItem(
                    $course->getBookingItemByCode($data->getPropertyResearchDiscount())
                );
            }

            if ($data->getAccommodationBefore() !== 'NONE') {
                $booking->addBookingItemByCourseBookingItem(
                    $course->getBookingItemByCode($data->getAccommodationBefore())
                );
            }

            //Persist the new items
            $this->getParentProcess()->persistBooking();

            $this->setComplete();
        } else {
            $this->setComplete(false);
        }

        $this->setUpdated();
        $this->save();
    }
}
