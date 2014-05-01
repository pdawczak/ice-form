<?php

namespace Ice\FormBundle\Infrastructure\Minerva;

use Ice\FormBundle\Entity\CourseApplication;
use Ice\FormBundle\Entity\CourseApplicationStep;
use Ice\FormBundle\Entity\CourseApplicationFieldValue;
use Ice\MinervaClientBundle\Entity\CourseApplication as MinervaClientCourseApplication;
use Ice\MinervaClientBundle\Entity\CourseApplicationFieldValue as MinervaClientCourseApplicationFieldValue;
use Ice\MinervaClientBundle\Entity\CourseApplicationStep as MinervaClientCourseApplicationStep;

/**
 * Converts JanusClient User entities to Account entities.
 *
 * Class JanusClientToUserAccountAdapter
 * @package Ice\FormBundle\Infrastructure\JanusClient
 */
class MinervaClientCourseApplicationAdapter
{
    /**
     * @param MinervaClientCourseApplication $mcApplication
     * @param $courseId
     * @param $applicantId
     * @return CourseApplication
     */
    public function getCourseApplication(MinervaClientCourseApplication $mcApplication, $courseId, $applicantId)
    {
        $steps = [];

        $mcSteps = $mcApplication->getCourseApplicationSteps();

        //Sort the steps by their order
        usort($mcSteps, function($a, $b) {
            if ($a->getOrder() < $b->getOrder()){
                return -1;
            }
            if ($a->getOrder() > $b->getOrder()){
                return 1;
            }
            return 0;
        });

        foreach ($mcSteps as $mcStep) {

            $step = (new CourseApplicationStep(
                $mcStep->getStepName(),
                $mcStep->getStepVersion(),
                $mcStep->getDescription(),
                $mcStep->getOrder()
            ));

            $step->setComplete($mcStep->isComplete());

            foreach ($mcStep->getFieldValues() as $mcValue) {
                $step->setValue(new CourseApplicationFieldValue(
                    $mcValue->getFieldName(),
                    $mcValue->getOrder(),
                    $mcValue->getDescription(),
                    $mcValue->getValue()
                ));
            }

            $step->setDirty(false);

            $steps[] = $step;
        }
        $application = (new CourseApplication($steps, $courseId, $applicantId))
            ->setId($mcApplication->getId())
        ;
        return $application;
    }

    /**
     * @param CourseApplication $application
     * @return MinervaClientCourseApplication
     */
    public function getMinervaClientCourseApplication(CourseApplication $application)
    {
        $mcSteps = [];
        foreach ($application->getSteps() as $step) {

            $mcValues = [];
            foreach ($step->getValues() as $value) {
                $mcValues[] = (new MinervaClientCourseApplicationFieldValue())
                    ->setFieldName($value->getName())
                    ->setDescription($value->getDescription())
                    ->setOrder($value->getOrder())
                    ->setValue($value->getValue())
                ;
            }

            $mcSteps[] = (new MinervaClientCourseApplicationStep())
                ->setStepName($step->getName())
                ->setStepVersion($step->getVersion())
                ->setOrder($step->getOrder())
                ->setCompleted($step->isComplete() ? new \DateTime() : null)
                ->setDescription($step->getDescription())
                ->setFieldValues($mcValues)
            ;
        }

        $application = (new MinervaClientCourseApplication())
            ->setCourseApplicationSteps($mcSteps)
        ;

        return $application;
    }
}
