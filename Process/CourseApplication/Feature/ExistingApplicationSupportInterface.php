<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

interface ExistingApplicationSupportInterface
{
    /**
     * @param int $courseId
     * @param $iceId
     * @param $applicationId
     * @return $this
     */
    public function initialiseWithApplication($courseId, $iceId, $applicationId);
}
