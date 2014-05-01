<?php

namespace Ice\FormBundle\Process\CourseApplication\Form;

use Ice\FormBundle\Process\CourseApplication\StepInterface;


interface StepFormFactoryInterface
{
    /**
     * @param StepInterface $step
     * @return FormInterface
     */
    public function getForm(StepInterface $step);
}
