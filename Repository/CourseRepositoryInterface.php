<?php

namespace Ice\FormBundle\Repository;

use Ice\FormBundle\Entity\Course;

interface CourseRepositoryInterface
{
    /**
     * Return a Course entity
     *
     * @param $courseId
     * @return Course
     */
    public function find($courseId);
}
