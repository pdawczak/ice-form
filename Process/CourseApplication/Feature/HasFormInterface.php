<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

use Ice\FormBundle\Form\Validation\ValidatableInterface;
use Symfony\Component\Form\FormTypeInterface;

interface HasFormInterface extends ViewableInterface
{
    /**
     * @return FormTypeInterface
     */
    public function getFormType();

    /**
     * @return mixed
     */
    public function getFormOptions();

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param ValidatableInterface $validation
     * @return mixed
     */
    public function onValidSubmission(ValidatableInterface $validation);
}
