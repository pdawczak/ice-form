<?php

namespace Ice\FormBundle\Entity;

use Ice\FormBundle\Exception\EntityNotReadyException;

class CourseApplication
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $applicantId;

    /**
     * @var int
     */
    private $courseId;

    /**
     * @var CourseApplicationStep[]
     */
    private $steps;

    /**
     * @var \DateTime
     */
    private $completed;

    public function __construct(array $steps = null, $courseId, $applicantId = null, $applicationId = null, $completed = null)
    {
        $this->steps = $steps;
        $this->courseId = $courseId;
        $this->applicantId = $applicantId;
        $this->id = $applicationId;
        $this->completed = $completed;
    }

    /**
     * @return string
     */
    public function getApplicantId()
    {
        return $this->applicantId;
    }

    /**
     * @param string $applicantId
     * @return CourseApplication
     */
    public function setApplicantId($applicantId)
    {
        $this->applicantId = $applicantId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Ice\FormBundle\Entity\CourseApplicationStep[]
     */
    public function getSteps()
    {
        $this->ensureLoaded();
        return $this->steps;
    }

    /**
     * Get a particular step by its reference, null if none match
     *
     * @param $reference
     * @return CourseApplicationStep|null
     */
    public function getStep($reference)
    {
        foreach ($this->getSteps() as $step) {
            if ($step->getName() == $reference) {
                return $step;
            }
        }
        return null;
    }

    /**
     * Get the first step not marked as complete. (Optionally greater than a given step.order)
     *
     * @param int $afterOrder
     * @return CourseApplicationStep|null
     */
    public function getFirstIncompleteStep($afterOrder = 0)
    {
        foreach ($this->getSteps() as $step) {
            if ($step->getOrder() > $afterOrder && !$step->isComplete()) {
                return $step;
            }
        }
        return null;
    }

    /**
     * @param int $id
     * @return CourseApplication
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        if (!$this->getId()) {
            return false;
        }

        $this->ensureLoaded();
        return $this->completed !== null;
    }

    /**
     *
     */
    public function isLoaded()
    {
        return $this->steps !== null;
    }

    /**
     * @throws \Ice\FormBundle\Exception\EntityNotReadyException
     */
    private function ensureLoaded()
    {
        if (!$this->isLoaded()) {
            throw new EntityNotReadyException();
        }
    }
}
