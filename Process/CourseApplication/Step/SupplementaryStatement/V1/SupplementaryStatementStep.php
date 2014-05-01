<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\SupplementaryStatement\V1;

use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class SupplementaryStatementStep extends AbstractFormStep implements
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
        return 'Supplementary information';
    }

    public function getFormType()
    {
        return new SupplementaryStatementType();
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = new SupplementaryStatementData();
        }
        return $this->data;
    }
}
