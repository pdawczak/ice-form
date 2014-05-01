<?php

namespace Ice\FormBundle\Process\CourseApplication\Exception;

class StepNotReadyException extends \RuntimeException
{
    protected $message = "An action was attempted on a step when that step is not ready";
}
