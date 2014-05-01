<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\SupplementaryStatement\V1;

class SupplementaryStatementData
{
    private $SupplementaryStatement;

    /**
     * @param mixed $SupplementaryStatement
     * @return SupplementaryStatementData
     */
    public function setSupplementaryStatement($SupplementaryStatement)
    {
        $this->SupplementaryStatement = $SupplementaryStatement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSupplementaryStatement()
    {
        return $this->SupplementaryStatement;
    }
}
