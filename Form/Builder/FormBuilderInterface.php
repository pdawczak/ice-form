<?php

namespace Ice\FormBundle\Form\Builder;

use Ice\FormBundle\Form\Type\FormTypeInterface;
use Ice\FormBundle\Form\FormInterface;

interface FormBuilderInterface
{
    /**
     * Adds a new field to this group. A field must have a unique reference within
     * the group. Otherwise the existing field is overwritten.
     *
     * If you add a nested group, this group should also be represented in the
     * object hierarchy.
     *
     * @param string|integer|FormBuilderInterface $child
     * @param string|FormTypeInterface            $type
     * @param array                               $options
     *
     * @return FormBuilderInterface The builder object.
     */
    public function add($child, $type = null, array $options = array());

    /**
     * Return a Form instance
     *
     * @return FormInterface
     */
    public function getForm();
}
