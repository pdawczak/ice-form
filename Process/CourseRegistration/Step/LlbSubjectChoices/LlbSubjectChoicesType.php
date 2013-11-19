<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\LlbSubjectChoices;

use Ice\JanusClientBundle\Exception\ValidationException;
use JMS\Serializer\Tests\Fixtures\Person;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
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

class LlbSubjectChoicesType extends AbstractRegistrationStep
{
    const CATEGORY_TUITION = 5;

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
        return 'Subject choices';
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null)
    {
        $this->getForm()->bind($request);
        /** @var $entity LlbSubjectChoices */
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
                    substr($bookingItem->getCode(),0,7) !== 'SUBJECT'
                ) {
                    $newBookingItems[] = $bookingItem;
                }
            }

            $booking->setBookingItems($newBookingItems);

            //Add back any items which we are responsible for
            if ($choices = $entity->getSubjectChoices()) {

                foreach ($choices as $choice) {
                    $booking->addBookingItemByCourseBookingItem(
                        $course->getBookingItemByCode($choice)
                    );
                }
            }

            //Persist the new items
            $this->getParentProcess()->getMinervaClient()->updateBooking(
                $this->getParentProcess()->getRegistrantId(),
                $this->getParentProcess()->getCourseId(),
                $booking
            );
        }

        foreach (array(
                     1 => 'subjectChoices',
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
            $contact = LlbSubjectChoices::fromStepProgress($this->getStepProgress());
        } else {
            $contact = new LlbSubjectChoices();
        }
        $this->setEntity($contact);
        $this->setPrepared();
    }

    public function getHtmlTemplate()
    {
        return 'LlbSubjectChoices.html.twig';
    }

    /**
     * Add fields to the form based on the values in $data, which may or may not be from a request.
     *
     * @param Course $course
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     */
    protected function buildCustomForm($course, FormBuilderInterface $builder)
    {
        $choices = $this->getSubjectChoices($course);

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
                'choices' => $enabledChoiceKeys,
                'multiple' => true,
                'min' => 2,
                'minMessage' => 'You must select at least {{ limit }} subjects.',
                'multipleMessage' => 'One or more of your chosen subjects is no longer available.'
            ))
        );

        $options = array(
            'label' => 'Your subject choices',
            'choices' => $choiceKeysLabels,
            'constraints' => isset($constraints) ? $constraints : array(),
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'invalid_message' => 'Please choose a valid option. Some choices are only valid in combination with others so you may need to re-select multiple options.',
            'attr' => array(
                'class' => 'ajax',
            ),
        );

        $builder->add('subjectChoices', 'choice', $options);
    }

    /**
     * Get available subject choices
     *
     * @param Course $course
     * @return array
     */
    private function getSubjectChoices($course)
    {
        $options = [];

        foreach ($course->getBookingItems() as $item) {
            if ($item->getCategory() === self::CATEGORY_TUITION && substr($item->getCode(), 0, 7) === 'SUBJECT') {
                $options[$item->getCode()] = [
                    'label'=>sprintf("%s (£%.02f)", $item->getTitle(), $item->getPrice()/100),
                    'enabled'=>$item->isInStock()
                ];
            }

        }

        return $options;
    }
}
