<?php
namespace Ice\FormBundle\Process\MakePayment;

use Ice\FormBundle\Process\MakePayment;

class StepProgress
{
    /**
     * @var \DateTime
     */
    private $dateBegan;

    /**
     * @var \DateTime
     */
    private $dateUpdated;

    /**
     * @var \DateTime
     */
    private $dateCompleted;

    /**
     * @param \DateTime $dateBegan
     * @return StepProgress
     */
    public function setDateBegan($dateBegan)
    {
        $this->dateBegan = $dateBegan;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateBegan()
    {
        return $this->dateBegan;
    }

    /**
     * @param \DateTime $dateCompleted
     * @return StepProgress
     */
    public function setDateCompleted($dateCompleted)
    {
        $this->dateCompleted = $dateCompleted;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCompleted()
    {
        return $this->dateCompleted;
    }

    /**
     * @param \DateTime $dateUpdated
     * @return StepProgress
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @return bool
     */
    public function isBegun()
    {
        return $this->getDateBegan() !== null;
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return $this->getDateCompleted() !== null;
    }

    /**
     * @param bool $complete
     * @return $this
     */
    public function setComplete($complete = true)
    {
        if ($complete && !$this->isComplete()) {
            $this->setDateCompleted(new \DateTime());
        }
        if (!$complete && $this->isComplete()) {
            $this->setDateCompleted(null);
        }
        return $this;
    }
}