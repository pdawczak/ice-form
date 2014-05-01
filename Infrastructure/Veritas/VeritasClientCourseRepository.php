<?php

namespace Ice\FormBundle\Infrastructure\Veritas;

use Ice\FormBundle\Repository\CourseRepositoryInterface;
use Ice\VeritasClientBundle\Entity\Course as VeritasClientCourse;
use Ice\FormBundle\Entity\Course;
use Ice\VeritasClientBundle\Service\VeritasClient;

class VeritasClientCourseRepository implements CourseRepositoryInterface
{
    /**
     * @var \Ice\VeritasClientBundle\Service\VeritasClient
     */
    private $client;

    /**
     * @var VeritasClientCourseAdapter
     */
    private $adapter;

    /**
     * @var Course[]
     */
    private $cachedCourses = array();

    public function __construct(
        VeritasClient $client,
        VeritasClientCourseAdapter $adapter
    ) {
        $this->client = $client;
        $this->adapter = $adapter;
    }

    public function find($courseId)
    {
        if (!$this->isCourseInCache($courseId)) {
            $this->cacheCourse(
                $this->adapter->getCourse($this->client->getCourse($courseId))
            );
        }
        return $this->getCachedAccount($courseId);
    }

    public function reload(Course $course)
    {
        $this->cacheCourse(
            $this->adapter->getCourse($this->client->getCourse($course->getId()))
        );
        return $this->getCachedAccount($course->getId());
    }

    public function injectCourseFromVeritasClient(VeritasClientCourse $vcCourse)
    {
        $this->cacheCourse($this->adapter->getCourse($vcCourse));
        return $this;
    }

    /**
     * Store an account in the local cache
     *
     * @param Course $course
     * @return $this
     */
    protected function cacheCourse(Course $course)
    {
        $this->cachedCourses[$course->getId()] = $course;
        return $this;
    }

    protected function getCachedAccount($courseId)
    {
        return $this->cachedCourses[$courseId];
    }

    protected function isCourseInCache($courseId)
    {
        return isset($this->cachedCourses[$courseId]);
    }
}
