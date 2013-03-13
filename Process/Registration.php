<?php
namespace Ice\FormBundle\Process;

use Ice\FormBundle\Process\Registration\Step as Step;

class Registration extends AbstractProcess{


    /**
     * @param string $reference
     * @return Step\AbstractRegistrationStep
     */
    public function getStepByReference($reference){
        $className = 'Ice\\FormBundle\\Process\\Registration\\Step\\'.ucwords($reference);
        //$step = new $className($this);

        $reflectionClass = new \ReflectionClass($className);

        //$reflectionClass->newInstanceArgs(array($this));

        return $reflectionClass->newInstance($this);
    }
}