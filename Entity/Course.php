<?php

namespace Ice\FormBundle\Entity;

class Course
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var CourseApplicationRequirement[]
     */
    private $courseApplicationRequirements;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Ice\FormBundle\Entity\CourseApplicationRequirement[] $courseApplicationRequirements
     * @return Course
     */
    public function setCourseApplicationRequirements($courseApplicationRequirements)
    {
        $this->courseApplicationRequirements = $courseApplicationRequirements;
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Entity\CourseApplicationRequirement[]
     */
    public function getCourseApplicationRequirements()
    {
        return $this->courseApplicationRequirements;
    }
}
