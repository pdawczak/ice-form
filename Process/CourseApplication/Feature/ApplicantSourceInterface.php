<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

use Ice\FormBundle\Repository\AccountRepositoryInterface;

interface ApplicantSourceInterface
{
    /**
     * @return string|null
     */
    public function getAccountIceId();
}
