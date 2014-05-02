<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\ApplicationStatement\V1;

use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class ApplicationStatementStep extends AbstractFormStep implements
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
        return new ApplicationStatementType();
    }

    public function getTitle()
    {
        return 'Application part 1';
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = new ApplicationStatementData();
        }
        return $this->data;
    }
}
