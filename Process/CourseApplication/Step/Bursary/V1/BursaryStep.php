<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Bursary\V1;

use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class BursaryStep extends AbstractFormStep implements
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
        return new BursaryType();
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = new BursaryData();
        }
        return $this->data;
    }

    public function getJavaScriptTemplatePath()
    {
        return 'CourseApplication/Step/Bursary.js.twig';
    }
}
