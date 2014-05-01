<?php

namespace Ice\FormBundle\Entity;

class CourseApplicationStep
{
    private $version;

    private $name;

    private $values = [];

    private $completed;

    private $order;

    private $description;

    private $dirty = false;

    public function __construct($name, $version, $description, $order)
    {
        $this->version = $version;
        $this->name = $name;
        $this->description = $description;
        $this->order = $order;
    }

    public function setValue(CourseApplicationFieldValue $value)
    {
        $this->values[$value->getName()] = $value;
        $this->dirty = true;
        return $this;
    }

    public function hasValue($key)
    {
        return isset($this->values[$key]);
    }

    public function getValue($key)
    {
        return $this->values[$key];
    }

    /**
     * @return CourseApplicationFieldValue[]
     */
    public function getValues()
    {
        return $this->values;
    }

    public function isComplete()
    {
        return $this->completed != null;
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
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $description
     * @return CourseApplicationStep
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $order
     * @return CourseApplicationStep
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param bool $dirty
     * @return $this
     */
    public function setDirty($dirty = false)
    {
        $this->dirty = $dirty;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDirty()
    {
        return $this->dirty;
    }

    /**
     * @param bool $complete
     * @return $this
     */
    public function setComplete($complete = true)
    {
        $this->completed = $complete;
        return $this;
    }
}
