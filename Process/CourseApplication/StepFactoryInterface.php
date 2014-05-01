<?php

namespace Ice\FormBundle\Process\CourseApplication;

use Ice\FormBundle\Process\CourseApplication\CourseApplication;

interface StepFactoryInterface
{
    public function getStep($reference, $version);
}
