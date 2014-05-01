<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\CppaStatement\V1;

use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class CppaStatementStep extends AbstractFormStep implements
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

    public function getTitle()
    {
        return 'CPPA statement';
    }

    public function getFormType()
    {
        return new CppaStatementType();
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = new CppaStatementData();
        }
        return $this->data;
    }
}
