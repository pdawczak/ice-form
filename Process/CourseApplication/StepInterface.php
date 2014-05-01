<?php
namespace Ice\FormBundle\Process\CourseApplication;

use Ice\FormBundle\Rendering\RendererInterface;
use Ice\MinervaClientBundle\Entity\CourseApplicationStep;

interface StepInterface
{
    public function isComplete();

    public function getFormType();

    public function getData();

    public function getReference();

    public function isInitialised();
}
