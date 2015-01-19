<?php

namespace Ice\FormBundle\Entity;

class AvailableBursary
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $price;

    /**
     * @var bool
     */
    private $availableInFrontEnd;

    /**
     * @var bool
     */
    private $availableInBackEnd;


    /**
     * @param string $code
     * @param string $title
     * @param int $price
     * @param boolean $availableInFrontEnd
     * @param boolean $availableInBackEnd
     */
    public function __construct($code, $title, $price, $availableInFrontEnd, $availableInBackEnd)
    {
        $this->code =$code;
        $this->title = $title;
        $this->price = $price;
        $this->availableInFrontEnd = $availableInFrontEnd;
        $this->availableInBackEnd = $availableInBackEnd;
    }

    /**
     * @return boolean
     */
    public function isAvailableInBackEnd()
    {
        return $this->availableInBackEnd;
    }

    /**
     * @return boolean
     */
    public function isAvailableInFrontEnd()
    {
        return $this->availableInFrontEnd;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function isIvyRoseHood()
    {
        return stripos($this->title, 'Ivy Rose Hood') !== false;
    }

    public function isJamesStuart()
    {
        return stripos($this->title, 'James Stuart') !== false;
    }

    public function isCambridgeUniversityPress()
    {
        return stripos($this->title, 'Cambridge University Press') !== false;
    }
}
