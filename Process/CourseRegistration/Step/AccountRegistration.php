<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step;

use Symfony\Component\HttpFoundation\Request;

class AccountRegistration extends AbstractRegistrationStep{

    public function processSubmission(){

    }

    public function isComplete(){

    }

    /**
     * Sets up entities, pre-populates fields
     *
     * @return mixed
     */
    public function prepare()
    {
        $this->setPrepared();
    }
}