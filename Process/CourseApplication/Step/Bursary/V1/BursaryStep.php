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

    public function getTemplateVars()
    {
        $vars = parent::getTemplateVars();

        /*
         * This version of the form step previously used a hard-coded template which always offered three bursaries.
         * The template modifications introduced for V2 show this information dynamically based on a 'bursaries'
         * template variable. To avoid having two versions of the template we pass the bursaries variable here too, but
         * populate it with static data.
         */
        $vars['bursaries'] = [
            'irh' => [ 'value' => 500, 'title' => 'Ivy Rose Hood Memorial Bursary' ],
            'js' => [ 'value' => 150, 'title' => 'James Stuart Bursary' ],
            'cup' => [ 'value' => 200, 'title' => 'Cambridge University Press Bursary' ]
        ];

        return $vars;
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
