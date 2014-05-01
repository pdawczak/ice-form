<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Address\V1;

use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class AddressStep extends AbstractFormStep implements
    StepInterface,
    ExistingApplicationSupportInterface
{
    private $data = null;

    public function isAvailable()
    {
        return $this->applicationId !== null;
    }

    public function getFormOptions()
    {
        return ['step'=>$this];
    }

    public function getFormType()
    {
        return new AddressType();
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = new AddressData();
        }
        return $this->data;
    }

    public function getTitle()
    {
        return 'Contact';
    }
}
