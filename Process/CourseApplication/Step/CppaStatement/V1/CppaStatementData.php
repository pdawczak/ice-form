<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\CppaStatement\V1;

class CppaStatementData
{
    private $CppaStatement;

    /**
     * @param mixed $CppaStatement
     * @return CppaStatementData
     */
    public function setCppaStatement($CppaStatement)
    {
        $this->CppaStatement = $CppaStatement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCppaStatement()
    {
        return $this->CppaStatement;
    }
}
