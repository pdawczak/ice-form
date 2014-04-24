<?php

namespace Ice\FormBundle\Form;

interface FormInterface
{
    /**
     * Bind submitted data to this form.
     *
     * @param mixed $request
     */
    public function bind($request);

    /**
     * Checks whether the bound data is valid, and sets any validation errors on this form and any children.
     *
     * @return bool
     */
    public function isValid();
}
