<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

use Ice\FormBundle\Repository\CourseRepositoryInterface;

interface CourseRepositoryAwareInterface
{
    /**
     * @param CourseRepositoryInterface $courseRepository
     * @return $this
     */
    public function setCourseRepository($courseRepository);
}
