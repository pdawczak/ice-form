<?php

namespace Ice\FormBundle\Process\CourseApplication\Exception;

class StepNotDefinedException extends \RuntimeException
{
    protected $message = "An attempt was made to invoke a step which is not defined";
}
