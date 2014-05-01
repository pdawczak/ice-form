<?php

namespace Ice\FormBundle\Process\CourseApplication\View;

interface ViewableProcessInterface
{
    public function getSteps();

    public function getCurrentStep();
}
