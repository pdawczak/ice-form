<?php

namespace Ice\FormBundle\Process\CourseApplication\View;

use Ice\FormBundle\Process\CourseApplication\Feature\ViewableInterface;

interface ProcessViewInterface
{
    /**
     * @return ViewableInterface[]
     */
    public function getViewableSteps();

    /**
     * @return int
     */
    public function getNumberOfViewableSteps();

    /**
     * @return ViewableInterface
     */
    public function getCurrentStep();

    /**
     * @return string
     */
    public function getCurrentStepJavaScript();

    /**
     * @return string
     */
    public function getCurrentStepHtml();
}
