<?php
namespace Ice\FormBundle\Process;

use Ice\FormBundle\Process\Registration\Step as Step;

class Registration extends AbstractProcess{

    /**
     * @param string $reference
     * @return Step\AbstractRegistrationStep
     */
    public function getStepByReference($reference){
        $className = 'Step\\'.$reference;
        $step = new $className;
        return $step;
    }
}