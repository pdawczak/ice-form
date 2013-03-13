<?php

namespace Ice\FormBundle\Type\Registration;

use Symfony\Component\Form\AbstractType;

abstract class AbstractRegistrationType extends AbstractType{
    protected $registrationProgress;

    public function setRegistrationProgress($registrationProgress)
    {
        $this->registrationProgress = $registrationProgress;
        return $this;
    }

    public function getRegistrationProgress()
    {
        return $this->registrationProgress;
    }
}