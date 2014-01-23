<?php

namespace Ice\FormBundle\Process\CourseRegistration;

interface StepDirectorInterface
{
    /**
     * Gets an instance of a StepFactoryInterface given the unique string reference of the step
     *
     * @param string $reference
     * @return StepFactoryInterface
     */
    public function getStepFactory($reference);

    /**
     * Sets an instance of a StepFactoryInterface which will be returned by getStepFactory for a given reference
     *
     * @param string $reference
     * @param StepFactoryInterface $factory
     * @return StepDirectorInterface
     */
    public function setStepFactory($reference, StepFactoryInterface $factory);
}
