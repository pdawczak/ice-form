<?php

namespace Ice\FormBundle\Form;

use Ice\FormBundle\Form\Validation\ValidatableInterface;

interface FormInterface extends ValidatableInterface
{
    /**
     * Bind submitted data to this form.
     *
     * @param mixed $request
     */
    public function bind($request);

    /**
     * Returns the instance of a child form specified by reference
     *
     * @param string $childName
     * @return FormInterface
     */
    public function get($childName);
}
