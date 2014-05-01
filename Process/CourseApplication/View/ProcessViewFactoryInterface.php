<?php

namespace Ice\FormBundle\Process\CourseApplication\View;

use Ice\FormBundle\Form\FormInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ViewableInterface;
use Ice\FormBundle\Process\CourseApplication\StepList;
use Ice\FormBundle\Process\CourseApplicationProcess;

interface ProcessViewFactoryInterface
{
    public function getProcessView(CourseApplicationProcess $process, StepList $stepList, ViewableInterface $currentStep, FormInterface $form = null);
}
