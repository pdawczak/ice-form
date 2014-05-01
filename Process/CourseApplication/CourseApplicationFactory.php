<?php

namespace Ice\FormBundle\Process\CourseApplication;

use Ice\FormBundle\Entity\Course;
use Ice\FormBundle\Entity\CourseApplication;
use Ice\FormBundle\Entity\CourseApplicationStep;
use Ice\FormBundle\Repository\CourseRepositoryInterface;

class CourseApplicationFactory
{
    /**
     * @var \Ice\FormBundle\Repository\CourseRepositoryInterface
     */
    private $courseRepository;

    public function buildNewFromCourse($courseId, $applicantId = null)
    {
        $course = $this->courseRepository->find($courseId);

        $steps = [];
        foreach ($course->getCourseApplicationRequirements() as $requirement) {
            $steps[] = (new CourseApplicationStep(
                $requirement->getReference(),
                $requirement->getVersion(),
                $requirement->getDescription(),
                $requirement->getOrder()
           ));
        }
        $application = new CourseApplication($steps, $courseId, $applicantId);

        return $application;
    }

    public function buildResumed($applicationId, $courseId, $applicantId)
    {
        $application = new CourseApplication(null, $courseId, $applicantId, $applicationId);
        return $application;
    }

    /**
     * @param \Ice\FormBundle\Repository\CourseRepositoryInterface $courseRepository
     * @return CourseApplicationFactory
     */
    public function setCourseRepository($courseRepository)
    {
        $this->courseRepository = $courseRepository;
        return $this;
    }
}
