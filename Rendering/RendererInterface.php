<?php

namespace Ice\FormBundle\Rendering;

interface RendererInterface
{
    /**
     * Renders a template.
     *
     * @param string $template       A template reference
     * @param array  $parameters     An array of parameters to pass to the template
     *
     * @return string The evaluated template as a string
     *
     * @throws \RuntimeException if the template cannot be rendered
     */
    public function render($template, array $parameters = array());
}
