<?php
namespace Ice\FormBundle\Process;

use Ice\FormBundle\Process\CourseApplication\CourseApplicationType;
use Ice\FormBundle\Process\CourseApplication\Feature\AnonymousSupportInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ApplicantSourceInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\KnownUserWithoutApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\Flow\CourseApplicationFlow;
use Ice\FormBundle\Process\CourseApplication\Initialisation\StepInitialiser;
use Ice\FormBundle\Process\CourseApplication\Persistence\FieldValueSourceInterface;
use Ice\FormBundle\Process\CourseApplication\Persistence\StateToDataConverter;
use Ice\FormBundle\Process\CourseApplication\Rendering\StepRendererInterface;
use Ice\FormBundle\Process\CourseApplication\StepHandlerListBuilder;
use Ice\FormBundle\Process\CourseApplication\Submission\SubmissionHandler;
use Ice\FormBundle\Process\CourseApplication\View\ProcessViewFactoryInterface;
use Ice\FormBundle\Process\CourseApplication\View\ProcessViewInterface;
use Ice\FormBundle\Process\CourseRegistration\PreProcessor\ItemValueCalculator;
use Ice\FormBundle\Process\CourseRegistration\Step as Step;

use Ice\FormBundle\Process\CourseApplication\StepDirectorInterface;
use Ice\FormBundle\Repository\CourseApplicationRepositoryInterface;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\MinervaClientBundle\Entity\BookingItem;
use Ice\MinervaClientBundle\Entity\Category;
use Ice\MinervaClientBundle\Entity\RegistrationProgress;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Ice\FormBundle\Entity\Course;
use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\AcademicInformation;
use Ice\FormBundle\Process\CourseApplication\StepInterface;
use Ice\FormBundle\Process\CourseApplication\Form\StepFormFactory;

use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ice\FormBundle\Process\CourseApplication\StepList;

class CourseApplicationProcess
{
    /** @var StepInterface */
    private $currentStepHandler;

    /** @var StepList */
    private $stepList;

    /**
     * @var StepHandlerListBuilder
     */
    private $stepHandlerListBuilder;

    /**
     * @var \Ice\FormBundle\Entity\CourseApplication
     */
    private $courseApplication;

    /**
     * @var \Ice\FormBundle\Repository\CourseApplicationRepositoryInterface
     */
    private $courseApplicationRepository;

    /**
     * @var string
     */
    private $url;

    /**
     * @var StepFormFactory
     */
    private $formFactory;

    /**
     * @var CourseApplication\View\ProcessViewInterface
     */
    private $view;

    /**
     * @var CourseApplication\View\ProcessViewFactoryInterface
     */
    private $viewFactory;

    /**
     * @var CourseApplication\Submission\SubmissionHandler
     */
    private $submissionHandler;

    /**
     * @param \Ice\FormBundle\Entity\CourseApplication $courseApplication
     * @param \Ice\FormBundle\Repository\CourseApplicationRepositoryInterface $courseApplicationRepository
     * @param CourseApplication\StepHandlerListBuilder $stepCollectionBuilder
     * @param StepFormFactory $formFactory
     * @param CourseApplication\View\ProcessViewFactoryInterface $viewFactory
     * @param CourseApplication\Submission\SubmissionHandler $submissionHandler
     */
    public function __construct(
        \Ice\FormBundle\Entity\CourseApplication $courseApplication,
        CourseApplicationRepositoryInterface $courseApplicationRepository,
        StepHandlerListBuilder $stepCollectionBuilder,
        StepFormFactory $formFactory,
        ProcessViewFactoryInterface $viewFactory,
        SubmissionHandler $submissionHandler
    )
    {
        $this->courseApplicationRepository = $courseApplicationRepository;
        $this->courseApplication = $courseApplication;
        $this->stepHandlerListBuilder = $stepCollectionBuilder;
        $this->formFactory = $formFactory;
        $this->viewFactory = $viewFactory;
        $this->submissionHandler = $submissionHandler;
    }

    /**
     * @return StepList
     */
    public function getStepList()
    {
        if (null === $this->stepList) {
            $this->ensureApplicationLoaded();
            $this->stepList = $this->stepHandlerListBuilder->buildFromApplication($this->courseApplication);
            foreach ($this->stepList->getHandlers() as $handler) {
                $stepInitialiser = new StepInitialiser(new StateToDataConverter());
                $stepInitialiser->initialiseStep($handler, $this->courseApplication);
            }
        }
        return $this->stepList;
    }

    public function getFlow()
    {
        if (!isset($this->flow)) {
            $this->flow = new CourseApplicationFlow($this->getStepList());
        }
        return $this->flow;
    }

    /**
     * Processes an incoming submission (if any), advances the step if appropriate and prepares
     *
     * @param Request $request
     */
    public function processRequest(Request $request)
    {
        $flow = $this->getFlow();
        $stepInitialiser = new StepInitialiser(new StateToDataConverter());

        if ($request->isXmlHttpRequest()) {
            $this->processAjaxRequest($request);
        } else if ($request->getMethod() === 'POST') {

            $this->submissionHandler->processSubmission(
                $this->getStepList(),
                $request,
                $this->formFactory,
                $this->courseApplication,
                $flow
            );

            if ($this->courseApplicationRepository->canPersist($this->courseApplication)) {
                $this->courseApplicationRepository->persistAndFlush($this->courseApplication);
            }

            $this->currentStepHandler = $flow->getCurrentStepHandler();

/**
            if (null !== ($stepReference = $request->get('stepReference', null))) {
                $request->attributes->remove('stepReference');
                $submittedStep = $this->getStepList()->getStepByReference($stepReference);
                $this->setCurrentStep($submittedStep);
                $this->initialiseStep($submittedStep);

                $stepState = $this->courseApplication->getStep($submittedStep->getReference());

                $form = $this->formFactory->getForm($submittedStep);
                $form->bind($request);

                if($submittedStep instanceof ApplicantSourceInterface) {
                    if (
                        !$this->courseApplication->getApplicantId() &&
                        $submittedStep->getAccountIceId()
                    ) {
                        $this->courseApplication->setApplicantId($submittedStep->getAccountIceId());
                    }
                }

                if ($form->isValid()) {
                    $data = $submittedStep->getData();
                    if ($data instanceof FieldValueSourceInterface) {
                        $data->setFieldValues($stepState);
                    }
                }

                $stepState->setComplete($submittedStep->isComplete());

                if ($this->courseApplicationRepository->canPersist($this->courseApplication)) {
                    $this->courseApplicationRepository->persistAndFlush($this->courseApplication);
                }

                if ($submittedStep->isComplete()) {
                    $firstIncompleteStep = null;
                    $wholeProcessComplete = true;
                    foreach ($this->getSteps() as $firstIncompleteStep) {
                        if (!$firstIncompleteStep->isComplete()) {
                            $wholeProcessComplete = false;
                            break;
                        }
                    }
                    if ($wholeProcessComplete) {
                        if ($this->getProgress()->getCompleted() === null) {
                            $this->getProgress()->setCompleted(new \DateTime());
                        }
                        return;
                    } else {
                        try {
                            $this->setCurrentStepByIndex($submittedStep->getIndex() + 1);
                        } catch (\OutOfBoundsException $e) {
                            $this->setCurrentStep($firstIncompleteStep);
                        }
                    }
                } else {
                    $this->setCurrentStep($submittedStep);
                }
            }**/
        } else {
            $stepInitialiser->initialiseStep($flow->getCurrentStepHandler(), $this->courseApplication);
            $this->currentStepHandler = $flow->getCurrentStepHandler();
        }
    }

    private function ensureApplicationLoaded()
    {
        if (!$this->courseApplication->isLoaded()) {
            $this->courseApplication = $this->courseApplicationRepository->find(
                $this->courseApplication->getId(),
                $this->courseApplication->getCourseId(),
                $this->courseApplication->getApplicantId()
            );
        }
    }

    protected function processAjaxRequest(Request $request)
    {
        if (($progress = $this->getProgress()) && (null !== ($stepReference = $request->get('stepReference', null)))) {
            foreach ($progress->getStepProgresses() as $step) {
                if ($step->getStepName() === $stepReference) {
                    $submittedStep = $this->createStepByReference($stepReference,$step->getStepVersion());
                    $submittedStep->processAjaxRequest($request);
                    return;
                }
            }
        } else {
            return new Response('AJAX response not supported when no step reference is supplied or no progress is available.', 412);
        }
    }

    /**
     * @param \Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep $currentStep
     * @return CourseRegistration
     */
    public function setCurrentStep($currentStep)
    {
        $this->currentStepHandler = $currentStep;
        return $this;
    }

    /**
     * @param string $reference
     * @return CourseRegistration
     */
    public function setCurrentStepByReference($reference)
    {
        $this->currentStepHandler = $this->getStepList()->getStepByReference($reference);
        return $this;
    }

    public function setCurrentStepByIndex($index)
    {
        $flow = $this->getFlow();
        $stepList = $this->getStepList()->getHandlers();
        $this->currentStepHandler = reset($stepList);
        $flow->setCurrentStepHandler($this->currentStepHandler);
        for ($i=1; $i<$index; $i++) {
            $flow->setCurrentStepHandler($this->getStepList()->getHandlerAfter($flow->getCurrentStepHandler()));
        }
        return $this;
    }

    /**
     * @return StepInterface
     */
    public function getCurrentStep()
    {
        $this->ensureApplicationLoaded();
        if (null === $this->currentStepHandler) {
            foreach ($this->courseApplication->getSteps() as $step) {
                if (!$step->isComplete()) {
                    $stepHandler = $this->stepHandlerListBuilder->buildStep($step->getName(), $step->getVersion());
                    if($stepHandler->isInitialised() || $this->initialiseStep($stepHandler)) {
                        $this->currentStepHandler = $stepHandler;
                        break;
                    }
                }
            }
        }
        if (null === $this->currentStepHandler) {
            foreach ($this->getSteps() as $step) {
                if ($step->isInitialised() || $this->initialiseStep($step)) {
                    $this->currentStepHandler = $step;
                    break;
                }
            }
            if (!$this->currentStepHandler) {
                throw new \RuntimeException("No available course application steps");
            }
        }
        return $this->currentStepHandler;
    }

    /**
     * @return \Ice\FormBundle\Process\CourseApplication\View\ProcessViewInterface
     */
    public function getView()
    {
        if (!$this->view) {
            $this->view = $this->viewFactory->getProcessView(
                $this,
                $this->getStepList(),
                $this->currentStepHandler,
                $this->formFactory->getForm($this->currentStepHandler)
            );
        }
        return $this->view;
    }

    /**
     * Returns a partial representation of the current step.
     *
     * Not all steps will require have an Ajax Response.
     *
     * @return Response
     */
    public function getStepAjaxResponse()
    {
        return $this->getAjaxResponse();
    }

    /**
     * Whether the current step has an Ajax response.
     *
     * @return bool
     */
    public function stepSupportsAjaxResponse()
    {
        return true;
        return $this->getCurrentStep()->supportsAjaxResponse();
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        if (!$this->courseApplication->isComplete()) {
            return false;
        }

        foreach ($this->courseApplication->getSteps() as $stepState) {
            if (!$stepState->isComplete()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $url
     * @return CourseApplicationProcess
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function isModeRegisterAccount()
    {
        return !$this->courseApplication->getApplicantId();
    }

    public function isModeResumeApplication()
    {
        return !$this->isModeRegisterAccount() && $this->courseApplication->getId() === null;
    }

    /**
     * @return \Ice\FormBundle\Entity\CourseApplication
     */
    public function getCourseApplication()
    {
        return $this->courseApplication;
    }
}
