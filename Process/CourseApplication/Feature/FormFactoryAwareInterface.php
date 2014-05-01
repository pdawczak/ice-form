<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

use Symfony\Component\Form\FormFactoryInterface;

interface FormFactoryAwareInterface
{
    /**
     * @param FormFactoryInterface $formFactory
     * @return $this
     */
    public function setFormFactory($formFactory);
}
