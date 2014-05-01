<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

use Ice\FormBundle\Process\CourseApplication\StepInterface;

interface ViewableInterface extends StepInterface
{
    /**
     * Return the user-friendly name of this step
     *
     * @return string
     */
    public function getTitle();

    /**
     * Return the vars that will be available to the HTML and JavaScript templates
     *
     * @return string
     */
    public function getTemplateVars();

    /**
     * @return string
     */
    public function getHtmlTemplatePath();

    /**
     * @return string
     */
    public function getJavaScriptTemplatePath();
}
