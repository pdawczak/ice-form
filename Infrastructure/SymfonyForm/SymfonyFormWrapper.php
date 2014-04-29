<?php

namespace Ice\FormBundle\Infrastructure\SymfonyForm;

use Ice\FormBundle\Form\FormInterface;
use Symfony\Component\Form\FormError;

class SymfonyFormWrapper implements FormInterface, SymfonyFormWrapperInterface
{
    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $symfonyForm;

    /**
     * @param \Symfony\Component\Form\FormInterface $symfonyForm
     */
    public function __construct(\Symfony\Component\Form\FormInterface $symfonyForm)
    {
        $this->symfonyForm = $symfonyForm;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSymfonyForm()
    {
        return $this->symfonyForm;
    }

    /**
     * @param string $error
     * @param string $propertyPath
     * @return $this
     */
    public function addError($error, $propertyPath = '.')
    {
        $propertyPath = explode('.', trim($propertyPath, '.'));
        $currentForm = $this->symfonyForm;
        foreach ($propertyPath as $pathElement) {
            $currentForm = $currentForm->get($pathElement);
        }
        $currentForm->addError(new FormError($error));
        return $this;
    }

    public function bind($request)
    {
        $this->symfonyForm->bind($request);
    }

    public function isValid()
    {
        return $this->symfonyForm->isValid();
    }

    public function get($childName)
    {
        return new self($this->symfonyForm->get($childName));
    }
}
