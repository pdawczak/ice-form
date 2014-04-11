<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step;

use Ice\FormBundle\Process\CourseRegistration;
use Ice\MinervaClientBundle\Entity\BookingItem;
use Ice\MinervaClientBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Ice\MinervaClientBundle\Entity\StepProgress;

abstract class AbstractRegistrationStep extends AbstractType
{
    protected $title;

    /** @var string */
    protected $reference;

    /** @var \Ice\FormBundle\Process\CourseRegistration */
    private $parentProcess;

    /** @var \Ice\MinervaClientBundle\Entity\StepProgress */
    private $stepProgress;

    /** @var int */
    private $indexCache;

    /** @var Form */
    private $form;

    /** @var object */
    private $entity;

    /** @var bool */
    private $prepared = false;

    /** @var bool */
    private $continueClicked = false;

    /** @var int */
    protected $version = null;

    /**
     * Maps a child form to a specific step order
     *
     * E.g.
     * array(
     *  1 => 'Foo',
     *  3 => 'Bar',
     *  4 => 'FooBar',
     * )
     *
     * @var array
     */
    protected $childFormOrder = array();

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('stepReference', 'hidden', array(
            'data' => $this->getReference(),
            'mapped' => false
        ))
            ->addEventListener(FormEvents::PRE_BIND, function (FormEvent $e) {
                $data = $e->getData();
                $data['stepReference'] = $this->getReference();
                if (isset($data['continue'])) {
                    unset($data['continue']);
                    $this->continueClicked = true;
                    $e->setData($data);
                }
            }, 1);
    }

    /**
     * @param CourseRegistration $parentProcess
     * @param string|null $reference
     */
    public function __construct(CourseRegistration $parentProcess, $reference = null, $version = null)
    {
        $this->parentProcess = $parentProcess;
        $this->reference = $reference;
        $this->version = $version;
    }

    public function getTitle()
    {
        //The step reference, camelCase to Title case
        return ucfirst(strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $this->getReference())));
    }

    public function renderHtml(array $vars = array())
    {
        $vars['form'] = $this->getForm()->createView();
        $vars['url'] = $this->getParentProcess()->getUrl();
        if ($this->getStepProgress() && $this->getStepProgress()->getBegan() === null) {
            $this->getStepProgress()->setBegan(new \DateTime);
            $this->save();
        }
        return $this->getParentProcess()->getTemplating()->render('Registration/Step/' . $this->getHtmlTemplate(), $vars);
    }

    public function renderJavaScript(array $vars = array())
    {
        $vars['form'] = $this->getForm()->createView();
        $vars['url'] = $this->getParentProcess()->getUrl();
        if ($this->getStepProgress() && $this->getStepProgress()->getBegan() === null) {
            $this->getStepProgress()->setBegan(new \DateTime);
            $this->save();
        }
        return $this->getParentProcess()->getTemplating()->render('Registration/Step/' . $this->getJavaScriptTemplate(), $vars);
    }

    public function processAjaxRequest(Request $request)
    {
        $this->getParentProcess()->setAjaxResponse(new Response('Not supported'));
    }

    /**
     * Whether the current step has an Ajax response.
     *
     * @return bool
     */
    public function supportsAjaxResponse()
    {
        return false;
    }

    /**
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    protected function getForm()
    {
        if (null === $this->form) {
            $this->form = $this->getParentProcess()->getFormFactory()->create($this, $this->getEntity());
        }
        return $this->form;
    }

    public function getIndex()
    {
        if (null === $this->indexCache) {
            foreach ($this->getParentProcess()->getSteps() as $index => $step) {
                if ($this === $step) {
                    $this->indexCache = $index;
                    break;
                }
            }
        }
        return $this->indexCache;
    }

    /**
     * @return string
     */
    public function getHtmlTemplate()
    {
        return 'default.html.twig';
    }

    /**
     * @return string
     */
    public function getJavaScriptTemplate()
    {
        return 'default.js.twig';
    }

    /**
     * @param \Ice\FormBundle\Process\CourseRegistration $parentProcess
     * @return AbstractRegistrationStep
     */
    public function setParentProcess($parentProcess)
    {
        $this->parentProcess = $parentProcess;
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Process\CourseRegistration
     */
    public function getParentProcess()
    {
        return $this->parentProcess;
    }

    /**
     * Default and naive implementation to process a request.
     *
     * Relies on a mapping being specified in $this->childFormOrder and only persists the
     * values to Minerva.
     *
     * Should be enough for very simple registration steps.
     *
     * @param Request|null $request The request will be bound to the form if it is present. If not, it is assumed that it was bound earlier.
     */
    public function processRequest(Request $request = null)
    {
        if ($request) {
            $this->getForm()->bind($request);
        }

        $this->setStepProgressValues(
            $this->getEntity(),
            $this->childFormOrder,
            $this->getForm(),
            $this->getStepProgress()
        );

        if ($this->isContinueClicked() && $this->getForm()->isValid()) {
            $this->setComplete();
        } else {
            $this->setComplete(false);
        }

        $this->setUpdated();
        $this->save();
    }

    public function getName()
    {
        return '';
    }

    /**
     * Persists answers to StepProgress
     *
     * @param $entity
     * @param array $formOrder
     * @param FormInterface $form
     * @param StepProgress $progress
     */
    protected function setStepProgressValues($entity, array $formOrder, FormInterface $form, StepProgress $progress)
    {
        foreach ($formOrder as $order => $fieldName) {
            $getter = 'get' . ucfirst($fieldName);
            $progress->setFieldValue(
                $fieldName,
                $order,
                $this->getFieldDescription($fieldName),
                $entity->$getter()
            );
        }
    }

    /**
     * Gets the description for a given field.
     *
     * @param $fieldName
     * @return string
     */
    protected function getFieldDescription($fieldName)
    {
        try {
            return $this->getForm()->get($fieldName)->getConfig()->getOption('label');
        } catch (\OutOfBoundsException $e) {
            return $fieldName;
        }
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function isCurrent()
    {
        return $this->getReference() === $this->getParentProcess()->getCurrentStep()->getReference();
    }

    public function setComplete($complete = true)
    {
        if ($complete && !$this->isComplete()) { //Change to complete
            $this->getStepProgress()->setCompleted(new \DateTime());
        } else if ($this->isComplete() && !$complete) { //Change to incomplete
            $this->getStepProgress()->setCompleted(null);
        }
    }

    public function setUpdated()
    {
        $this->getStepProgress()->setUpdated(new \DateTime());
    }

    public function isComplete()
    {
        if (null === $this->getStepProgress()) {
            return false;
        }
        return null !== $this->getStepProgress()->getCompleted();
    }

    /**
     * The entity that the form will be bound to.
     *
     * @throws \RuntimeException if the entity has not yet been prepared
     * @return \stdClass
     */
    public function getEntity()
    {
        if (null === $this->entity) {
            throw new \RuntimeException('Entity has not been prepared');
        }
        return $this->entity;
    }

    /**
     * @param $entity
     * @return AbstractRegistrationStep
     */
    protected function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param boolean $prepared
     * @return AbstractRegistrationStep
     */
    protected function setPrepared($prepared = true)
    {
        $this->prepared = $prepared;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrepared()
    {
        return $this->prepared;
    }

    /**
     * Sets up entities, pre-populates fields
     *
     * @return mixed
     */
    abstract public function prepare();

    /**
     * @param \DateTime|null $dateOrNull
     * @return null
     */
    private function dateToString($dateOrNull)
    {
        if (null === $dateOrNull) {
            return null;
        }
        return $dateOrNull->format('Y-m-d H:i:s');
    }

    public function save()
    {
        $values = array(
            'stepName' => $this->getStepProgress()->getStepName(),
            'order' => $this->getStepProgress()->getOrder(),
            'description' => $this->getStepProgress()->getDescription(),
            'began' => $this->dateToString($this->getStepProgress()->getBegan()),
            'updated' => $this->dateToString($this->getStepProgress()->getUpdated()),
            'completed' => $this->dateToString($this->getStepProgress()->getCompleted()),
            'registrationFieldValues' => array()
        );
        foreach ($this->getStepProgress()->getFieldValues() as $fieldValue) {
            $values['registrationFieldValues'][] = array(
                'fieldName' => $fieldValue->getFieldName(),
                'value' => $fieldValue->getValueSerialized(),
                'order' => $fieldValue->getOrder(),
                'description' => $fieldValue->getDescription()
            );
        }

        $this->getParentProcess()->getMinervaClient()->setRegistrationStep(
            $this->getParentProcess()->getRegistrantId(),
            $this->getParentProcess()->getCourseId(),
            $values
        );
    }

    /**
     * @param $stepProgress
     * @return $this
     */
    public function setStepProgress($stepProgress)
    {
        $this->stepProgress = $stepProgress;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Entity\StepProgress
     */
    public function getStepProgress()
    {
        return $this->stepProgress;
    }

    /**
     * By default, a step is available only if the registrant and course are known.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->areRegistrantAndCourseKnown();
    }

    /**
     * Return true if the registrant ID and course ID are both available.
     *
     * @return bool
     */
    protected function areRegistrantAndCourseKnown()
    {
        return $this->getParentProcess()->getRegistrantId() && $this->getParentProcess()->getCourseId();
    }

    /**
     * @param $continueClicked
     * @return $this
     */
    public function setContinueClicked($continueClicked)
    {
        $this->continueClicked = $continueClicked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isContinueClicked()
    {
        return $this->continueClicked;
    }

    /**
     * Called by the parent process when the booking item specified is no longer valid (typically because it is out of
     * stock). If this step is responsible for this item it should mark itself incomplete.
     *
     * If the step has any responsibility over this item it should return true.
     *
     * @param BookingItem $item
     * @return bool
     */
    public function invalidateBookingItem(BookingItem $item)
    {
        return false;
    }

    /**
     * Adds/Persist registrant attributes
     * @param array $fieldNames form field names to add as registrant attributes
     */
    public function persistRegistrantAttributes(array $fieldNames)
    {

        $entity = $this->getEntity();

        $attributes = [];
        foreach ($fieldNames as $fieldName) {

            $getter = 'get' . ucfirst($fieldName);
            $value = $entity->$getter();

            if (!$value) {
                $value = '';
            }

            $attributes[$fieldName] = (string)$value;
        }

        $iceId = $this->getParentProcess()->getRegistrantId();
        $this->getParentProcess()
            ->getJanusClient()
            ->setAttributes($iceId, $iceId, $attributes);
    }
}
