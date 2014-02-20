<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\UniversityCard;

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

class UniversityCardType extends AbstractRegistrationStep
{
    /**
     * @var array
     */
    protected $childFormOrder = [
        1 => 'photoMethod'
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photoMethod', 'choice', array(
                'label' => 'Please indicate how you intend to send your photo.',
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'choices' => array(
                    'Email' => 'I agree to email my photograph',
                    'Post' => 'I agree to post my photograph',
                ),
                'constraints' => array(
                    new NotBlank()
                )
            ));
        parent::buildForm($builder, $options);
    }

    public function getHtmlTemplate()
    {
        return 'UniversityCard.html.twig';
    }

    public function prepare()
    {
        $this->setEntity(UniversityCard::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
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