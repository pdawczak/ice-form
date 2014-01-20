<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\ConfirmNotCommonLaw;

use Ice\MinervaClientBundle\Entity\StepProgress;
use Symfony\Component\Validator\Constraints as Assert;

class ConfirmNotCommonLaw
{
    /**
     * @var bool
     * @Assert\True(message="You must confirm to continue")
     */
    private $confirmNotCommonLaw;

    /**
     * @param mixed $confirmNotCommonLaw
     * @return ConfirmNotCommonLaw
     */
    public function setConfirmNotCommonLaw($confirmNotCommonLaw)
    {
        $this->confirmNotCommonLaw = $confirmNotCommonLaw;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfirmNotCommonLaw()
    {
        return $this->confirmNotCommonLaw;
    }

    public static function fromStepProgress(StepProgress $progress)
    {
        return new self();
    }
}