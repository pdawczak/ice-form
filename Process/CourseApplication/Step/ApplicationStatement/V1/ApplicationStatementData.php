<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\ApplicationStatement\V1;

class ApplicationStatementData
{
    private $applicationStatement;

    /**
     * @param mixed $applicationStatement
     * @return ApplicationStatementData
     */
    public function setApplicationStatement($applicationStatement)
    {
        $this->applicationStatement = $applicationStatement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplicationStatement()
    {
        return $this->applicationStatement;
    }
}
