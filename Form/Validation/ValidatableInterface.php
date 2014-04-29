<?php

namespace Ice\FormBundle\Form\Validation;

interface ValidatableInterface
{
    /**
     * @param string $error
     * @param string $propertyPath
     * @return $this
     */
    public function addError($error, $propertyPath = '.');


    /**
     * Checks whether the bound data is valid, and sets any validation errors on this form and any children.
     *
     * @return bool
     */
    public function isValid();
}
