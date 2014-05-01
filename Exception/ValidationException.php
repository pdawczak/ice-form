<?php

namespace Ice\FormBundle\Exception;

class ValidationException extends \RuntimeException
{
    /**
     * @var array
     */
    private $errorsAsArray;

    /**
     * @param array $errors
     * @param string $message
     */
    public function __construct(array $errors, $message = "Error while validating an entity")
    {
        $this->errorsAsArray = $errors;
        parent::__construct($message);
    }

    /**
     * @return mixed
     */
    public function getErrorsAsArray()
    {
        return $this->errorsAsArray;
    }
}
