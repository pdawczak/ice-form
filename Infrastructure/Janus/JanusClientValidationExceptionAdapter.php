<?php

namespace Ice\FormBundle\Infrastructure\Janus;

use Ice\JanusClientBundle\Exception\ValidationException as JanusClientValidationException;
use Ice\FormBundle\Exception\ValidationException as NativeValidationException;

class JanusClientValidationExceptionAdapter
{
    public function getNativeException(JanusClientValidationException $jcve, $message = null)
    {
        if (!$message) {
            $message = $jcve->getMessage();
        }
        return new NativeValidationException($jcve->getForm()->getErrorsAsAssociativeArray(true), $message);
    }
}
