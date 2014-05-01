<?php

namespace Ice\FormBundle\Repository;

use Ice\FormBundle\Entity\CourseApplication;

interface CourseApplicationRepositoryInterface
{
    /**
     * Return a CourseApplication entity
     *
     * @param $applicationId
     * @param $courseId
     * @param $applicantId
     * @return mixed
     */
    public function find($applicationId, $courseId, $applicantId);

    /**
     * @param CourseApplication $courseApplication
     * @return mixed
     */
    public function persistAndFlush(CourseApplication $courseApplication);

    /**
     * Return true if the application is sufficiently complete to be persisted.
     *
     * @param CourseApplication $courseApplication
     * @return bool
     */
    public function canPersist(CourseApplication $courseApplication);
}
