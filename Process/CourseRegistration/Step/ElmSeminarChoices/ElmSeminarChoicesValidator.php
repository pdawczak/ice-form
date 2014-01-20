<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\ElmSeminarChoices;


use Symfony\Component\Validator\ExecutionContext;

class ElmSeminarChoicesValidator
{
    /**
     * @param ElmSeminarChoices $choices
     * @param ExecutionContext $context
     */
    public static function areChoicesValid(ElmSeminarChoices $choices, $context)
    {
        if (!$choices->getFirstChoice()) {
            $context->addViolationAtSubPath('firstChoice', "Please select a first choice");
        }

        if (!$choices->getSecondChoice()) {
            $context->addViolationAtSubPath('secondChoice', "Please select a second choice");
        }

        if (!$choices->getThirdChoice()) {
            $context->addViolationAtSubPath('thirdChoice', "Please select a third choice");
        }

        if (!$choices->getFirstChoice() || !$choices->getSecondChoice() || !$choices->getThirdChoice()) {
            return;
        }

        if ($choices->getFirstChoice() === $choices->getSecondChoice()) {
            $context->addViolationAtSubPath('secondChoice', "Second choice cannot be equal to the first");
        }

        if ($choices->getFirstChoice() === $choices->getThirdChoice()) {
            $context->addViolationAtSubPath('thirdChoice', "Third choice cannot be equal to the first");
        }

        if ($choices->getSecondChoice() === $choices->getThirdChoice()) {
            $context->addViolationAtSubPath('thirdChoice', "Third choice cannot be equal to the second");
        }
    }

    /**
     * @param ElmSeminarChoices $choices
     * @param ExecutionContext $context
     */
    public static function isHopeToGainValid(ElmSeminarChoices $choices, $context)
    {
        if (!$choices->getHopeToGain()) {
            $context->addViolationAtSubPath('hopeToGain', "This is a required field");
        }
    }
}