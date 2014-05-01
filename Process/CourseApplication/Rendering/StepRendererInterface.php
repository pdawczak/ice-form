<?php

namespace Ice\FormBundle\Process\CourseApplication\Rendering;

use Ice\FormBundle\Process\CourseApplication\Feature\ViewableInterface;

interface StepRendererInterface
{
    public function renderStepHtml(ViewableInterface $step, array $defaultVars = array(), array $overrideVars = array());

    public function renderStepJavaScript(ViewableInterface $step, array $defaultVars = array(), array $overrideVars = array());
}
