<?php

namespace Ice\FormBundle\Process\CourseApplication\Form;

use Ice\FormBundle\Infrastructure\SymfonyForm\FormBuilderFactory;
use Ice\FormBundle\Infrastructure\SymfonyForm\FormFactoryFacade;
use Ice\FormBundle\Process\CourseApplication\CourseApplicationType;
use Ice\FormBundle\Process\CourseApplication\StepInterface;
use Ice\FormBundle\Form\Builder\FormBuilderFactoryInterface;

class StepFormFactory implements StepFormFactoryInterface
{
    private $formBuilderFactory;

    private $forms = array();

    public function __construct(FormBuilderFactoryInterface $formBuilderFactory)
    {
        $this->formBuilderFactory = $formBuilderFactory;
    }

    public function getForm(StepInterface $step)
    {
        if (!isset($this->forms[$step->getReference()])) {
            $this->forms[$step->getReference()] = $this->formBuilderFactory->createBuilder(
                new CourseApplicationType(),
                [$step->getReference() => $step->getData()],
                ['step' => $step]
            )->getForm();
        }
        return $this->forms[$step->getReference()];
    }
}
