<?php

namespace Ice\FormBundle\Process;

interface CourseRegistrationFactoryInterface
{
    /**
     * Return a CourseRegistration instance linked to the specified courseId and (if applicable) registrant ICE id
     *
     * @param $courseId
     * @param null $registrantId
     * @return CourseRegistration
     */
    public function getCourseRegistrationProcess($courseId, $registrantId = null);
}
