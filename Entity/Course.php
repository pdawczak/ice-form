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
     * @var AvailableBursary[]
     */
    private $availableBursaries;

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

    /**
     * @return AvailableBursary[]
     */
    public function getAvailableBursaries()
    {
        return $this->availableBursaries;
    }

    /**
     * @param AvailableBursary[] $availableBursaries
     * @return Course
     */
    public function setAvailableBursaries($availableBursaries)
    {
        $this->availableBursaries = $availableBursaries;
        return $this;
    }
}
