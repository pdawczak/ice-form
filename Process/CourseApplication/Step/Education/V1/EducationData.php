<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Education\V1;

class EducationData
{
    private $highestQualification;
    private $recentInvolvement;

    /**
     * @param mixed $highestQualification
     * @return EducationData
     */
    public function setHighestQualification($highestQualification)
    {
        $this->highestQualification = $highestQualification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHighestQualification()
    {
        return $this->highestQualification;
    }

    /**
     * @param mixed $recentInvolvement
     * @return EducationData
     */
    public function setRecentInvolvement($recentInvolvement)
    {
        $this->recentInvolvement = $recentInvolvement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecentInvolvement()
    {
        return $this->recentInvolvement;
    }
}
