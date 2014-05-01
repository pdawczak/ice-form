<?php

namespace Ice\FormBundle\Process;

use Ice\FormBundle\Infrastructure\Veritas\VeritasClientCourseRepository;
use Ice\FormBundle\Process\CourseApplication\CourseApplicationFactory;
use Ice\FormBundle\Process\CourseApplication\Rendering\StepRendererInterface;
use Ice\FormBundle\Process\CourseApplication\StepCollectionBuilder;
use Ice\FormBundle\Process\CourseApplication\StepHandlerListBuilder;
use Ice\FormBundle\Process\CourseApplication\StepDirectorInterface;
use Ice\FormBundle\Process\CourseApplication\Submission\SubmissionHandler;
use Ice\FormBundle\Process\CourseApplication\View\ProcessViewFactoryInterface;
use Ice\FormBundle\Rendering\RendererInterface;
use Ice\FormBundle\Repository\CourseApplicationRepositoryInterface;
use Ice\JanusClientBundle\Service\JanusClient;
use Ice\MinervaClientBundle\Service\MinervaClient;
use Ice\VeritasClientBundle\Service\VeritasClient;
use Ice\FormBundle\Repository\CourseRepositoryInterface;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Form\FormFactoryInterface;
use Ice\FormBundle\Process\CourseApplication\Form\StepFormFactory;

class CourseApplicationProcessFactory implements CourseApplicationProcessFactoryInterface
{
    /**
     * @var CourseApplication\StepCollectionBuilder
     */
    private $stepCollectionBuilder;

    /**
     * @var StepFormFactory
     */
    private $formFactory;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    /**
     * @var CourseApplicationRepositoryInterface
     */
    private $courseApplicationRepository;

    /**
     * @var CourseApplication\View\ProcessViewFactoryInterface
     */
    private $processViewFactory;

    /**
     * @var CourseApplication\Submission\SubmissionHandler
     */
    private $submissionHandler;

    /**
     * @param CourseApplication\CourseApplicationFactory $courseApplicationFactory
     * @param CourseApplicationRepositoryInterface $courseApplicationRepository
     * @param StepHandlerListBuilder $stepCollectionBuilder
     * @param StepFormFactory $formFactory
     * @param CourseApplication\View\ProcessViewFactoryInterface $processViewFactory
     * @param CourseApplication\Submission\SubmissionHandler $submissionHandler
     * @internal param \Ice\FormBundle\Process\CourseApplication\Rendering\StepRendererInterface $renderer
     */
    public function __construct(
        CourseApplicationFactory $courseApplicationFactory,
        CourseApplicationRepositoryInterface $courseApplicationRepository,
        StepHandlerListBuilder $stepCollectionBuilder,
        StepFormFactory $formFactory,
        ProcessViewFactoryInterface $processViewFactory,
        SubmissionHandler $submissionHandler
    )
    {
        $this->courseApplicationRepository = $courseApplicationRepository;
        $this->stepCollectionBuilder = $stepCollectionBuilder;
        $this->formFactory = $formFactory;
        $this->courseApplicationFactory = $courseApplicationFactory;
        $this->processViewFactory = $processViewFactory;
        $this->submissionHandler = $submissionHandler;
    }

    /**
     * Return a CourseApplicationProcess instance linked to the specified courseId and (if applicable) applicant ICE id
     *
     * @param $courseId
     * @param null $applicantId
     * @return CourseRegistration
     */
    public function startCourseApplicationProcess($courseId, $applicantId = null)
    {
        $courseApplication = $this->courseApplicationFactory->buildNewFromCourse($courseId, $applicantId);

        $courseApplicationProcess = new CourseApplicationProcess(
            $courseApplication,
            $this->courseApplicationRepository,
            $this->stepCollectionBuilder,
            $this->formFactory,
            $this->processViewFactory,
            $this->submissionHandler
        );

        return $courseApplicationProcess;
    }

    public function resumeCourseApplicationProcess($applicationId, $courseId, $applicantId)
    {
        $courseApplication = $this->courseApplicationFactory->buildResumed($applicationId, $courseId, $applicantId);

        $courseApplicationProcess = new CourseApplicationProcess(
            $courseApplication,
            $this->courseApplicationRepository,
            $this->stepCollectionBuilder,
            $this->formFactory,
            $this->processViewFactory,
            $this->submissionHandler
        );

        return $courseApplicationProcess;
    }

    /**
     * @param \Ice\FormBundle\Repository\CourseRepositoryInterface $courseRepository
     * @return CourseApplicationProcessFactory
     */
    public function setCourseRepository($courseRepository)
    {
        $this->courseRepository = $courseRepository;
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Repository\CourseRepositoryInterface
     */
    public function getCourseRepository()
    {
        return $this->courseRepository;
    }
}
