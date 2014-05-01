<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

interface KnownUserWithoutApplicationSupportInterface
{
    /**
     * @param int $courseId
     * @param string $iceId
     * @return $this
     */
    public function initialiseWithUserWithoutApplication($courseId, $iceId);
}
