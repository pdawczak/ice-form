<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\StudentLoan\V1;

use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class StudentLoanStep extends AbstractFormStep implements
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
        return new StudentLoanType();
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = new StudentLoanData();
        }
        return $this->data;
    }

    public function getJavaScriptTemplatePath()
    {
        return 'CourseApplication/Step/EnglishLanguage.js.twig';
    }


}
