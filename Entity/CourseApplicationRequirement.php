<?php

namespace Ice\FormBundle\Entity;

class CourseApplicationRequirement
{
    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $order;

    /**
     * @param string $name
     * @param $version
     * @param $description
     * @param $order
     */
    public function __construct($name, $version, $description, $order)
    {
        $this->reference = $name;
        $this->version = $version;
        $this->description = $description;
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }
}
