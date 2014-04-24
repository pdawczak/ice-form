<?php

namespace Ice\FormBundle\Form\Options;

interface FormOptionsConfigurationInterface
{
    /**
     * An array of options which must be specified. This replaces any previous required options set
     *
     * @param array $requiredOptions
     * @return $this
     */
    public function setRequired(array $requiredOptions = array());

    /**
     * An array of optionName=>defaultValue pairs. This replaces the set of previously configured defaults
     *
     * @param array $defaultOptionValues
     * @return mixed
     */
    public function setDefaults(array $defaultOptionValues = array());
}
