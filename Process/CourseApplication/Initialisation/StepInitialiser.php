<?php

namespace Ice\FormBundle\Process\CourseApplication\Initialisation;

use Ice\FormBundle\Entity\CourseApplication;
use Ice\FormBundle\Process\CourseApplication\Feature\AnonymousSupportInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\KnownUserWithoutApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\Persistence\StateToDataConverter;
use Ice\FormBundle\Process\CourseApplication\Persistence\StateToDataConverterInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class StepInitialiser implements StepInitialiserInterface
{
    /**
     * @var \Ice\FormBundle\Process\CourseApplication\Persistence\StateToDataConverterInterface
     */
    private $stateToDataConverter;

    /**
     * @param StateToDataConverterInterface $stateToDataConverter
     */
    public function __construct(
        StateToDataConverterInterface $stateToDataConverter
    )
    {
        $this->stateToDataConverter = $stateToDataConverter;
    }

    /**
     * Initialise a step
     *
     * @param StepInterface $step
     * @param CourseApplication $courseApplication
     * @return mixed
     */
    public function initialiseStep(StepInterface $step, CourseApplication $courseApplication)
    {
        $step->setApplication($courseApplication);

        if ($courseApplication->getId()) {
            if ($step instanceof ExistingApplicationSupportInterface) {
                $step->initialiseWithApplication(
                    $courseApplication->getCourseId(),
                    $courseApplication->getApplicantId(),
                    $courseApplication->getId()
                );

                $converter = new StateToDataConverter();
                if ($stepState = $courseApplication->getStep($step->getReference())) {
                    $converter->setDataFromState($stepState, $step->getData());
                }

                return true;
            }
            return false;
        }

        if ($courseApplication->getApplicantId()) {
            if ($step instanceof KnownUserWithoutApplicationSupportInterface) {
                $step->initialiseWithUserWithoutApplication(
                    $courseApplication->getCourseId(),
                    $courseApplication->getApplicantId()
                );
                return true;
            }
            return false;
        }

        if ($step instanceof AnonymousSupportInterface) {
            $step->initialiseAnonymously($courseApplication->getCourseId());
            return true;
        }
        return false;
    }
}

