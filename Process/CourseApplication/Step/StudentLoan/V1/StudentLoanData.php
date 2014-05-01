<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\StudentLoan\V1;

class StudentLoanData
{
    private $studentLoanApplied;

    /**
     * @param mixed $studentLoanApplied
     * @return StudentLoanData
     */
    public function setStudentLoanApplied($studentLoanApplied)
    {
        $this->studentLoanApplied = $studentLoanApplied;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStudentLoanApplied()
    {
        return $this->studentLoanApplied;
    }
}
