<?php

namespace Ice\FormBundle\Entity;

class CourseApplicationFieldValue
{
    private $order;

    private $description;

    private $name;

    private $value;

    public function __construct($name, $order, $description, $value = null)
    {
        $this->order = $order;
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }
}
