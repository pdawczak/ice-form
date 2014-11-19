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

        foreach ($vcCourse->getCourseApplicationRequirements() as $vcRequirement)
        {
            $courseApplicationRequirements[] = new CourseApplicationRequirement(
                $vcRequirement->getCode(),
                $vcRequirement->getVersion(),
                $vcRequirement->getDescription()
            );
        }

        $course = (new Course($vcCourse->getId()))
            ->setCourseApplicationRequirements($courseApplicationRequirements)
        ;
        return $course;
    }
}
