<?php

namespace Ice\FormBundle\Process\CourseApplication;

use Ice\FormBundle\Form\Validation\ValidatableInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\FormFactoryAwareInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\HasFormInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ViewableInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Ice\FormBundle\Rendering\RendererInterface;
use Ice\FormBundle\Entity\CourseApplicationStep;

abstract class AbstractFormStep implements StepInterface, HasFormInterface, FormFactoryAwareInterface
{
    private $application;

    private $reference;

    private $version;

    private $stepState;

    private $initialised = false;

    protected $applicationId;

    protected $courseId;

    protected $iceId;

    private $formFactory;

    public function __construct($reference, $version)
    {
        $this->reference = $reference;
        $this->version = $version;
    }

    public function getTitle()
    {
        //The step reference, camelCase to Title case
        return ucfirst(strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $this->getReference())));
    }

    /**
     * @param mixed $application
     * @return AbstractFormStep
     */
    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Process\CourseApplication\CourseApplication
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return bool
     */
    protected function hasOwnState()
    {
        if ($this->stepState === null) {
            $this->stepState = $this->application->getStep($this->getReference());
        }
        return $this->stepState != false;
    }

    /**
     * @return CourseApplicationStep|null
     */
    protected function getOwnState()
    {
        if ($this->hasOwnState() && $this->stepState)
        {
            return $this->stepState;
        }
        return null;
    }

    public function isPrepared()
    {
        return $this->prepared;
    }

    public function getHtmlTemplatePath()
    {
        return 'CourseApplication/Step/'.ucfirst($this->getReference()).'.html.twig';
    }

    public function getTemplateVars()
    {
        return [];
    }

    /**
     * @param FormFactoryInterface $formFactory
     * @return $this
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;
        return $this;
    }

    /**
     * @return FormFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    public function getJavaScriptTemplatePath()
    {
        return 'CourseApplication/Step/default.js.twig';
    }

    public function isInitialised()
    {
        return $this->initialised;
    }

    protected function setInitialised($initialised = true)
    {
        $this->initialised = $initialised;
    }

    /**
     * @param ValidatableInterface $validation
     * @return mixed
     */
    public function onValidSubmission(ValidatableInterface $validation)
    {
        $this->getOwnState()->setComplete(true);
    }

    public function isComplete()
    {
        if (!$this->hasOwnState()) {
            return false;
        } else {
            return $this->getOwnState()->isComplete();
        }
    }

    /**
     * @return mixed
     */
    public function getFormOptions()
    {
        return [];
    }

    public function initialiseWithApplication($courseId, $iceId, $applicationId)
    {
        $this->iceId = $iceId;
        $this->courseId = $courseId;
        $this->applicationId = $applicationId;
    }
}
