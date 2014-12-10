<?php

namespace Ice\FormBundle\Infrastructure\Veritas;

use Ice\FormBundle\Entity\AvailableBursary;
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

        $i = 1;
        foreach ($vcCourse->getCourseApplicationRequirements() as $vcRequirement)
        {
            $courseApplicationRequirements[] = new CourseApplicationRequirement(
                $vcRequirement->getCode(),
                $vcRequirement->getVersion(),
                $vcRequirement->getDescription(),
                $i
            );
            $i++;
        }

        $bursariesAvailable = [];

        foreach ($vcCourse->getBookingItems() as $vcBookingItem)
        {
            if (strpos($vcBookingItem->getCode(), 'BURSARY-') !== 0) {
                continue;
            }

            $bursariesAvailable[] = new AvailableBursary(
                $vcBookingItem->getCode(),
                $vcBookingItem->getTitle(),
                $vcBookingItem->getPrice(),
                $vcBookingItem->getAvailableInBackend(),
                $vcBookingItem->getAvailableInFrontend()
            );
        }

        $course = (new Course($vcCourse->getId()))
            ->setCourseApplicationRequirements($courseApplicationRequirements)
            ->setAvailableBursaries($bursariesAvailable)
        ;
        return $course;
    }
}
