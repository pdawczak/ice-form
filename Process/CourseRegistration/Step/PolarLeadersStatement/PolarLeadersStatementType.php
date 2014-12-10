<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\PolarLeadersStatement;


use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class PolarLeadersStatementType extends AbstractRegistrationStep
{

    protected $childFormOrder = [
        1 => 'polarExperience',
        2 => 'hopeToGain'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('polarExperience', 'textarea', [
                'label' => 'Please state briefly (100 words maximum) what experience and knowledge you have of the polar regions',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('hopeToGain', 'textarea', [
                'label' => 'Please state briefly (200 words maximum) what you hope to gain from this course.',
                'constraints' => [
                    new NotBlank()
                ]
            ])
        ;
        parent::buildForm($builder, $options);
    }

    public function getTitle()
    {
        return 'Experience and knowledge';
    }

    public function getHtmlTemplate()
    {
        return 'PolarLeadersStatement.html.twig';
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
            $contact = PolarLeadersStatement::fromStepProgress($this->getStepProgress());
        } else {
            $contact = new PolarLeadersStatement();
        }
        $this->setEntity($contact);
        $this->setPrepared();
    }
}
