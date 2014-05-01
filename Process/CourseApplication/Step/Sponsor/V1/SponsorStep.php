<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Sponsor\V1;

use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class SponsorStep extends AbstractFormStep implements
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
        return new SponsorType();
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = new SponsorData();
        }
        return $this->data;
    }

    public function getJavaScriptTemplatePath()
    {
        return 'CourseApplication/Step/Sponsor.js.twig';
    }

    public function getTitle()
    {
        return 'Sponsorship';
    }
}
