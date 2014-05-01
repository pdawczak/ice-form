<?php

namespace Ice\FormBundle\Process;

interface CourseApplicationProcessFactoryInterface
{
    /**
     * Return a CourseApplicationProcess instance linked to the specified courseId and (if applicable) applicant ICE id
     *
     * @param $courseId
     * @param null $applicantId
     * @return CourseApplicationProcess
     */
    public function startCourseApplicationProcess($courseId, $applicantId = null);
}
