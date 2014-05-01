<?php

namespace Ice\FormBundle\Infrastructure\Veritas;

use Ice\FormBundle\Entity\CourseApplicationRequirement;
use Ice\VeritasClientBundle\Entity\Course as VeritasClientCourse;
use Ice\FormBundle\Entity\Course;

/**
 * Converts VeritasClient Course into a native Course
 *
 * Class VeritasClientCourseAdapter
 * @package Ice\FormBundle\Infrastructure\Veritas
 */
class VeritasClientCourseAdapter
{
    /**
     * @param VeritasClientCourse $vcCourse
     * @return Course
     */
    public function getCourse(VeritasClientCourse $vcCourse)
    {
        $courseApplicationRequirements = [];

        /**
         * TODO: This MUST be changed so that the requirements are built from the course
         */

        $courseApplicationRequirements[] = new CourseApplicationRequirement('account', '1.0.0', 'Account', 1);
        $courseApplicationRequirements[] = new CourseApplicationRequirement('address', '1.0.0', 'Address', 2);

        $course = (new Course($vcCourse->getId()))
            ->setCourseApplicationRequirements($courseApplicationRequirements)
        ;
        return $course;
    }
}
