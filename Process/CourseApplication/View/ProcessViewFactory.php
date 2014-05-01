<?php

namespace Ice\FormBundle\Process\CourseApplication\View;

use Ice\FormBundle\Entity\CourseApplication;
use Ice\FormBundle\Form\FormInterface;
use Ice\FormBundle\Form\View\FormViewFactoryInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ViewableInterface;
use Ice\FormBundle\Process\CourseApplication\Rendering\StepRendererInterface;
use Ice\FormBundle\Process\CourseApplication\StepList;
use Ice\FormBundle\Process\CourseApplicationProcess;

class ProcessViewFactory implements ProcessViewFactoryInterface
{
    /**
     * @var StepRendererInterface
     */
    private $renderer;

    /**
     * @var \Ice\FormBundle\Form\View\FormViewFactoryInterface
     */
    private $formViewFactory;

    /**
     * @param StepRendererInterface $renderer
     * @param \Ice\FormBundle\Form\View\FormViewFactoryInterface $formViewFactory
     */
    public function __construct(
        StepRendererInterface $renderer,
        FormViewFactoryInterface $formViewFactory
    )
    {
        $this->formViewFactory = $formViewFactory;
        $this->renderer = $renderer;
    }

    /**
     * @param \Ice\FormBundle\Process\CourseApplicationProcess $process
     * @param \Ice\FormBundle\Process\CourseApplication\StepList $stepList
     * @param \Ice\FormBundle\Process\CourseApplication\Feature\ViewableInterface $currentStep
     * @param \Ice\FormBundle\Form\FormInterface $form
     * @return ProcessView
     */
    public function getProcessView(
        CourseApplicationProcess $process,
        StepList $stepList,
        ViewableInterface $currentStep,
        FormInterface $form = null
    ) {
        return new ProcessView($stepList, $this->renderer, $currentStep, $this->formViewFactory->getView($form));
    }
}
