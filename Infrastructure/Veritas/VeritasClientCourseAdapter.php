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
        $courseApplicationRequirements[] = new CourseApplicationRequirement('education', '1.0.0', 'Education', 3);
        $courseApplicationRequirements[] = new CourseApplicationRequirement('applicationStatement', '1.0.0', 'Personal statement', 4);
        $courseApplicationRequirements[] = new CourseApplicationRequirement('cppaStatement', '1.0.0', 'CPPA Statement', 5);
        $courseApplicationRequirements[] = new CourseApplicationRequirement('englishLanguage', '1.0.0', 'English proficiency', 6);
        $courseApplicationRequirements[] = new CourseApplicationRequirement('supplementaryStatement', '1.0.0', 'Supplementary', 7);
        $courseApplicationRequirements[] = new CourseApplicationRequirement('studentLoan', '1.0.0', 'Student Loan', 8);
        $courseApplicationRequirements[] = new CourseApplicationRequirement('sponsor', '1.0.0', 'Sponsorship', 9);
        $courseApplicationRequirements[] = new CourseApplicationRequirement('bursary', '1.0.0', 'Bursary', 10);

        $course = (new Course($vcCourse->getId()))
            ->setCourseApplicationRequirements($courseApplicationRequirements)
        ;
        return $course;
    }
}
