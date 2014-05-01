<?php

namespace Ice\FormBundle\Process\CourseApplication\Submission;

use Ice\FormBundle\Process\CourseApplication\Feature\ApplicantSourceInterface;
use Ice\FormBundle\Process\CourseApplication\Form\StepFormFactory;
use Ice\FormBundle\Process\CourseApplication\Persistence\DataToStateConverterInterface;
use Ice\FormBundle\Process\CourseApplication\Persistence\FieldValueSourceInterface;
use Ice\FormBundle\Process\CourseApplication\StepList;
use Ice\FormBundle\Process\CourseApplication\Initialisation\StepInitialiserInterface;

class SubmissionHandler
{
    /**
     * @var StepInitialiserInterface
     */
    private $stepInitialiser;

    /**
     * @var \Ice\FormBundle\Process\CourseApplication\Persistence\DataToStateConverterInterface
     */
    private $dataToStateConverter;

    public function __construct(
        StepInitialiserInterface $stepInitialiser,
        DataToStateConverterInterface $dataToStateConverter
    )
    {
        $this->stepInitialiser = $stepInitialiser;
        $this->dataToStateConverter = $dataToStateConverter;
    }

    public function processSubmission(StepList $stepList,
                $request,
                StepFormFactory $formFactory,
                $courseApplication,
                $flow)
    {
        if (null !== ($stepReference = $request->get('stepReference', null))) {
            $request->attributes->remove('stepReference');
        }

        if ($continueClicked = ($request->get('continue', false) ? true : false)) {
            $request->attributes->remove('continue');
        }

        if (!$stepReference) {
            throw new \RuntimeException("Submission received but no step reference given");
        }

        $step = $stepList->getHandler($stepReference);

        if (!$step) {
            throw new \RuntimeException("No step with reference $stepReference is part of this process");
        }

        $flow->setCurrentStepHandler($step);

        $this->stepInitialiser->initialiseStep($step, $courseApplication);

        $form = $formFactory->getForm($step);

        $form->bind($request);


        if ($form->isValid()) {
            $step->onValidSubmission($form->get($step->getReference()));

            $data = $step->getData();
            $state = $courseApplication->getStep($step->getReference());

            $this->dataToStateConverter->setStateFromData($data, $state);
        }

        if($step instanceof ApplicantSourceInterface) {
            if (
                !$courseApplication->getApplicantId() &&
                $step->getAccountIceId()
            ) {
                $courseApplication->setApplicantId($step->getAccountIceId());
            }
        }

        if ($form->isValid()) {
            $flow->advance();
        }
    }
}
