<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Education\V1;

use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class EducationStep extends AbstractFormStep implements
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
        return new EducationType();
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = new EducationData();
        }
        return $this->data;
    }
}
