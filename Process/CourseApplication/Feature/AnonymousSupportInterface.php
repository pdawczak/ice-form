<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

interface AnonymousSupportInterface
{
    /**
     * @param int $courseId
     * @return $this
     */
    public function initialiseAnonymously($courseId);
}
