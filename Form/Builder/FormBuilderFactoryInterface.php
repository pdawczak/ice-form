<?php

namespace Ice\FormBundle\Form\Builder;

use Ice\FormBundle\Form\Type\FormTypeInterface;
use Ice\FormBundle\Form\Builder\FormBuilderInterface;

interface FormBuilderFactoryInterface
{
    /**
     * Returns a form builder
     *
     * @param string|FormTypeInterface $type    The type of the form
     * @param mixed $data    The initial data
     * @param array $options The options
     *
     * @return FormBuilderInterface The form named after the type
     */
    public function createBuilder($type = 'form', $data = null, array $options = array());
}
