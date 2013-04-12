<?php
namespace Ice\FormBundle\Process\PlaceOrder;

use Ice\FormBundle\Process\PlaceOrder;

use Ice\FormBundle\Process\PlaceOrder\Step\ChoosePlans\PlanChoice;

class Progress implements \Serializable{
    /**
     * @var array
     */
    private $stepProgresses = array();

    /**
     * @var PlanChoice[]
     */
    private $planChoices = array();

    /**
     * @param string $reference
     * @param mixed $stepProgress
     * @return Progress
     */
    public function setStepProgress($reference, $stepProgress)
    {
        $this->stepProgresses[$reference] = $stepProgress;
        return $this;
    }

    /**
     * @return array
     */
    public function getStepProgresses()
    {
        return $this->stepProgresses;
    }

    public function getStepProgress($reference)
    {
        return $this->stepProgresses[$reference];
    }

    public function serialize(){

    }

    public function unserialize($serialized){

    }

    /**
     * @param PlanChoice[] $planChoices
     * @return $this
     */
    public function setPlanChoices($planChoices)
    {
        $this->planChoices = $planChoices;
        return $this;
    }

    /**
     * @return Step\ChoosePlans\PlanChoice[]
     */
    public function getPlanChoices()
    {
        return $this->planChoices;
    }
}