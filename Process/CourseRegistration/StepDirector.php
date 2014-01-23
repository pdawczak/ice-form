<?php

namespace Ice\FormBundle\Process\CourseRegistration;

class StepDirector implements StepDirectorInterface
{
    /**
     * @var StepFactoryInterface
     */
    private $defaultFactory;

    /**
     * @param StepFactoryInterface $defaultStepFactory
     */
    public function __construct(StepFactoryInterface $defaultStepFactory)
    {
        $this->defaultFactory = $defaultStepFactory;
    }

    /**
     * @var array|StepFactoryInterface
     */
    private $factories = [];

    /**
     * Gets an instance of a StepFactoryInterface given the unique string reference of the step
     *
     * @param string $reference
     * @return StepFactoryInterface
     */
    public function getStepFactory($reference)
    {
        if (isset($this->factories[$reference])) {
            return $this->factories[$reference];
        }
        return $this->defaultFactory;
    }

    /**
     * Sets an instance of a StepFactoryInterface which will be returned by getStepFactory for a given reference
     *
     * @param string $reference
     * @param StepFactoryInterface $factory
     * @return StepDirectorInterface
     */
    public function setStepFactory($reference, StepFactoryInterface $factory)
    {
        $this->factories[$reference] = $factory;
        return $this;
    }
}
