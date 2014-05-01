<?php

namespace Ice\FormBundle\Rendering;

use Symfony\Component\Templating\EngineInterface as TemplatingEngine;

class TwigRenderer implements RendererInterface
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $engine;

    /**
     * @param TemplatingEngine $engine
     */
    public function __construct(TemplatingEngine $engine)
    {
        $this->engine = $engine;
    }


    /**
     * Renders a template.
     *
     * @param string $template       A template reference
     * @param array $parameters     An array of parameters to pass to the template
     *
     * @return string The evaluated template as a string
     *
     * @throws \RuntimeException if the template cannot be rendered
     */
    public function render($template, array $parameters = array())
    {
        return $this->engine->render($template, $parameters);
    }
}
