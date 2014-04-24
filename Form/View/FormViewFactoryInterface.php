<?php

namespace Ice\FormBundle\Form\View;

use Ice\FormBundle\Form\FormInterface;

interface FormViewFactoryInterface
{
    /**
     * Returns a FormViewInterface for the given FormInterface instance
     *
     * @param FormInterface $form
     * @return FormViewInterface
     */
    public function getView(FormInterface $form);
}
