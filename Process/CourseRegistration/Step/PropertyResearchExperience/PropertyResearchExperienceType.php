<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\PropertyResearchExperience;


use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\MinervaClientBundle\Entity\BookingItem;
use Ice\VeritasClientBundle\Entity\Course;
use Symfony\Component\Validator\Constraints\NotBlank;

class PropertyResearchExperienceType extends AbstractRegistrationStep
{

    protected $childFormOrder = [
        1 => 'cvMethod',
        2 => 'propertyResearchExperience',
        3 => 'confirmPropertyResearchExperience'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('propertyResearchExperience', 'textarea', [
                'label' => 'Please provide details of qualifications relating to property, business and finance, including membership of recognised bodies or institutions.',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('cvMethod', 'choice', array(
                'label' => 'Please indicate how you intend to send your CV.',
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'choices' => array(
                    'Email' => 'I agree to email my CV',
                    'Post' => 'I agree to post my CV',
                ),
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('confirmPropertyResearchExperience', 'checkbox', [
                'label' => 'I confirm I will have had at least three years’ experience in the field by the course start date.',
                'constraints' => [
                    new NotBlank(
                        ['message' => 'You must have had experience in the field to enrol on this course.']
                    )
                ]
            ])
        ;
        parent::buildForm($builder, $options);
    }

    public function getTitle()
    {
        return 'Experience and qualifications';
    }

    public function getHtmlTemplate()
    {
        return 'PropertyResearchExperience.html.twig';
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
            $contact = PropertyResearchExperience::fromStepProgress($this->getStepProgress());
        } else {
            $contact = new PropertyResearchExperience();
        }
        $this->setEntity($contact);
        $this->setPrepared();
    }
}
