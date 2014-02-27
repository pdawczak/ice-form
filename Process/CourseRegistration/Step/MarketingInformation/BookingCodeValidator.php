<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation;

use Ice\FormBundle\Process\CourseRegistration\Step\MarketingInformation\BookingCodeHandler\BookingCodeHandlerManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BookingCodeValidator extends ConstraintValidator
{
    /**
     * @var
     */
    private $bookingCodeHandlerManager;

    public function __construct(BookingCodeHandlerManager $manager)
    {
        $this->bookingCodeHandlerManager = $manager;
    }

    /**
     * @param mixed $value
     * @param Constraint|BookingCodeConstraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($handler = $this->bookingCodeHandlerManager->getHandlerFor(
            $value,
            $constraint->course,
            $constraint->user
        )) {
            return $handler->validate($value, $constraint, $this->context);
        }

        $this->context->addViolation("This code is not recognised. If you have not been given a code, please leave this field blank.");
    }
}
