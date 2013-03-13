<?php

namespace Ice\FormBundle\Process;

class AbstractProcess{
    /** @var string */
    private $iceId;

    /**
     * @param $iceId
     * @return AbstractProcess
     */
    public final function setIceId($iceId)
    {
        $this->iceId = $iceId;
        return $this;
    }

    /**
     * @return string
     */
    public final function getIceId()
    {
        return $this->iceId;
    }
}