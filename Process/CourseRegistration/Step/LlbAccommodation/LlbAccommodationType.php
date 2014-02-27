<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\LlbAccommodation;

use Ice\JanusClientBundle\Exception\ValidationException;
use JMS\Serializer\Tests\Fixtures\Person;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ice\VeritasClientBundle\Entity\Course;
use Ice\JanusClientBundle\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;

class LlbAccommodationType extends AbstractRegistrationStep
{
    const CATEGORY_ACCOMMODATION = 6;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildCustomForm($this->getParentProcess()->getCourse(), $builder);
        parent::buildForm($builder, $options);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Accommodation';
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null)
    {
        $this->getForm()->bind($request);
        /** @var $entity LlbAccommodation */
        $entity = $this->getEntity();

        if ($this->isContinueClicked() && $this->getForm()->isValid()) {
            $course = $this->getParentProcess()->getCourse();

            $booking = $this->getParentProcess()->getBooking();

            $bookingItems = $booking->getBookingItems();

            //Remove all items
            $newBookingItems = [];

            //Add back any items we're not responsible for
            foreach ($bookingItems as $bookingItem) {
                if (
                    substr($bookingItem->getCode(),0,13) !== 'ACCOMMODATION'
                ) {
                    $newBookingItems[] = $bookingItem;
                }
            }

            $booking->setBookingItems($newBookingItems);

            //Add back any items which we are responsible for
            if ($choice = $entity->getAccommodation()) {
                $booking->addBookingItemByCourseBookingItem(
                    $course->getBookingItemByCode($choice)
                );
            }

            //Persist the new items
            $this->getParentProcess()->persistBooking();
        }

        foreach (array(
                     1 => 'accommodation',
                 )
                 as $order => $fieldName) {
            $getter = 'get' . ucfirst($fieldName);
            $value = $entity->$getter();

            $this->getStepProgress()->setFieldValue(
                $fieldName,
                $order,
                $this->getForm()->get($fieldName)->getConfig()->getOption('label'),
                $value
            );
        }

        if ($this->getForm()->isValid()) {
            $this->setComplete();
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
            $contact = LlbAccommodation::fromStepProgress($this->getStepProgress());
        } else {
            $contact = new LlbAccommodation();
        }
        $this->setEntity($contact);
        $this->setPrepared();
    }

    public function getHtmlTemplate()
    {
        return 'LlbAccommodation.html.twig';
    }

    /**
     * Add fields to the form based on the values in $data, which may or may not be from a request.
     *
     * @param Course $course
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     */
    protected function buildCustomForm($course, FormBuilderInterface $builder)
    {
        $choices = $this->getAccommodation($course);

        $enabledChoiceKeys = [];
        $choiceKeysLabels = [];

        foreach ($choices as $key => $choice) {
            if ($choice['enabled']) {
                $enabledChoiceKeys[] = $key;
            }
            $choiceKeysLabels[$key] = $choice['label'];
        }

        $constraints = array(
            new Choice(array(
                'choices' => $enabledChoiceKeys
            ))
        );

        $options = array(
            'label' => 'Your accommodation selection',
            'choices' => $choiceKeysLabels,
            'constraints' => isset($constraints) ? $constraints : array(),
            'required' => false,
            'expanded' => true,
            'multiple' => false,
            'invalid_message' => 'Please choose a valid option. Some choices are only valid in combination with others so you may need to re-select multiple options.',
            'attr' => array(
                'class' => 'ajax',
            ),
        );

        $builder->add('accommodation', 'choice', $options);
    }

    /**
     * Get available subject choices
     *
     * @param Course $course
     * @return array
     */
    private function getAccommodation($course)
    {
        $options = [];

        foreach ($course->getBookingItems() as $item) {
            if ($item->getCategory() === self::CATEGORY_ACCOMMODATION && substr($item->getCode(), 0, 13) === 'ACCOMMODATION') {
                $options[$item->getCode()] = [
                    'label'=>$item->getTitle(),
                    'enabled'=>$item->isInStock()
                ];
            }

        }

        return $options;
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
                'accommodation' => ['unavailableMessage' => 'Unavailable']
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
