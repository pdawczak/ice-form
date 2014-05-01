<?php

namespace Ice\FormBundle\Process\CourseApplication\View;

use Ice\FormBundle\Form\View\FormViewInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\HasFormInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ViewableInterface;
use Ice\FormBundle\Process\CourseApplication\Rendering\StepRendererInterface;
use Ice\FormBundle\Process\CourseApplication\StepList;
use Ice\FormBundle\Process\CourseApplicationProcess;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ProcessView implements ProcessViewInterface
{
    /**
     * @var \Ice\FormBundle\Process\CourseApplication\StepList
     */
    private $stepList;

    /**
     * @var \Ice\FormBundle\Process\CourseApplication\Rendering\StepRendererInterface
     */
    private $stepRenderer;

    /**
     * @var \Ice\FormBundle\Process\CourseApplication\StepList
     */
    private $viewableStepHandlers;

    /**
     * @var \Symfony\Component\Form\FormView
     */
    private $formView;

    /**
     * @var ViewableInterface
     */
    private $currentStepHandler;

    /**
     * @param \Ice\FormBundle\Process\CourseApplication\StepList $stepList
     */
    public function __construct(
        StepList $stepList,
        StepRendererInterface $stepRenderer,
        ViewableInterface $currentStep,
        FormViewInterface $formView = null
    )
    {
        $this->stepList = $stepList;
        $this->currentStepHandler = $currentStep;
        $this->stepRenderer = $stepRenderer;
        $this->formView = $formView;

        foreach ($this->stepList->getHandlers() as $handler) {
            if ($handler instanceof ViewableInterface) {
                $this->viewableStepHandlers[$handler->getReference()] = $handler;
            }
        }
    }

    /**
     * @return ViewableInterface[]
     */
    public function getViewableSteps()
    {
        return $this->viewableStepHandlers;
    }

    /**
     * @return ViewableInterface
     */
    public function getCurrentStep()
    {
        return $this->currentStepHandler;
    }

    /**
     * @return string
     */
    public function getCurrentStepJavaScript()
    {
        $defaults['form'] = $this->formView;
        return $this->stepRenderer->renderStepJavaScript($this->getCurrentStep(), $defaults);
    }

    /**
     * @return string
     */
    public function getCurrentStepHtml()
    {
        $defaults['form'] = $this->formView;
        return $this->stepRenderer->renderStepHtml($this->getCurrentStep(), $defaults);
    }

    /**
     * @return int
     */
    public function getNumberOfViewableSteps()
    {
        return count($this->stepList->getHandlers());
    }

    public function getCourseApplication()
    {
        return $this->stepList->getCourseApplication();
    }
}