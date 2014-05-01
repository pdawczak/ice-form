<?php

namespace Ice\FormBundle\Process\CourseApplication\Rendering;

use Ice\FormBundle\Process\CourseApplication\Feature\ViewableInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;
use Ice\FormBundle\Rendering\RendererInterface;

class DefaultStepRenderer implements StepRendererInterface
{
    /**
     * @var \Ice\FormBundle\Rendering\RendererInterface
     */
    private $renderer;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function renderStepHtml(ViewableInterface $step, array $defaultVars = array(), array $overrideVars = array())
    {
        $vars = array_merge($defaultVars, $step->getTemplateVars(), $overrideVars);
        return $this->renderer->render($step->getHtmlTemplatePath(), $vars);
    }

    public function renderStepJavaScript(ViewableInterface $step, array $defaultVars = array(), array $overrideVars = array())
    {
        $vars = array_merge($defaultVars, $step->getTemplateVars(), $overrideVars);
        return $this->renderer->render($step->getJavascriptTemplatePath(), $vars);
    }
}
