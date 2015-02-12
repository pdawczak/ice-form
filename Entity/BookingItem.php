<?php

namespace Ice\FormBundle\Entity;

class BookingItem
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var int
     */
    private $priceInPence;

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBursaryOrDiscount()
    {
        return $this->priceInPence < 0;
    }

    /**
     * @param int $priceInPence
     * @return $this
     */
    public function setPriceInPence($priceInPence)
    {
        $this->priceInPence = $priceInPence;
        return $this;
    }

    /**
     * @return int
     */
    public function getPriceInPence()
    {
        return $this->priceInPence;
    }
}
