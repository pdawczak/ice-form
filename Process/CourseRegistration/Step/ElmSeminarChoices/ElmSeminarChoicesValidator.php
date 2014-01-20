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
        if (!$choices->getSeminarChoicesFirstChoice()) {
            $context->addViolationAtSubPath('seminarChoicesFirstChoice', "Please select a first choice");
        }

        if (!$choices->getSeminarChoicesSecondChoice()) {
            $context->addViolationAtSubPath('seminarChoicesSecondChoice', "Please select a second choice");
        }

        if (!$choices->getSeminarChoicesThirdChoice()) {
            $context->addViolationAtSubPath('seminarChoicesThirdChoice', "Please select a third choice");
        }

        if (!$choices->getSeminarChoicesFirstChoice() ||
            !$choices->getSeminarChoicesSecondChoice() ||
            !$choices->getSeminarChoicesThirdChoice()) {
            return;
        }

        if ($choices->getSeminarChoicesFirstChoice() === $choices->getSeminarChoicesSecondChoice()) {
            $context->addViolationAtSubPath('seminarChoicesSecondChoice', "Second choice cannot be equal to the first");
        }

        if ($choices->getSeminarChoicesFirstChoice() === $choices->getSeminarChoicesThirdChoice()) {
            $context->addViolationAtSubPath('seminarChoicesThirdChoice', "Third choice cannot be equal to the first");
        }

        if ($choices->getSeminarChoicesSecondChoice() === $choices->getSeminarChoicesThirdChoice()) {
            $context->addViolationAtSubPath('seminarChoicesThirdChoice', "Third choice cannot be equal to the second");
        }
    }

    /**
     * @param ElmSeminarChoices $choices
     * @param ExecutionContext $context
     */
    public static function isSeminarChoicesPersonalStatementValid(ElmSeminarChoices $choices, $context)
    {
        if (!$choices->getSeminarChoicesPersonalStatement()) {
            $context->addViolationAtSubPath('seminarChoicesPersonalStatement', "This is a required field");
        }
    }
}