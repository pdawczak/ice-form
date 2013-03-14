<?php
namespace Ice\FormBundle\Process;

use Ice\FormBundle\Process\CourseRegistration\Step as Step;

class CourseRegistration extends AbstractProcess{


    /**
     * @param string $reference
     * @return Step\AbstractRegistrationStep
     */
    public function getStepByReference($reference){
        $className = 'Ice\\FormBundle\\Process\\CourseRegistration\\Step\\'.ucwords($reference);
        //$step = new $className($this);

        $reflectionClass = new \ReflectionClass($className);

        //$reflectionClass->newInstanceArgs(array($this));

        return $reflectionClass->newInstance($this);
    }
}