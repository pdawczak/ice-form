<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\FormInterface;
use Ice\FormBundle\Form\View\FormViewFactoryInterface;

class FormViewFactory implements FormViewFactoryInterface
{
    public function getView(FormInterface $form)
    {
        if (!($form instanceof SymfonyFormWrapperInterface))
        {
            throw new \Exception("Symfony FormViewFactory can only be used with form instances which implement SymfonyFormWrapperInterface");
        }
        return new FormViewFacade($form->getSymfonyForm()->createView());
    }
}
