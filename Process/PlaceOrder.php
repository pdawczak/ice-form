<?php
namespace Ice\FormBundle\Process;

use Doctrine\Common\Collections\ArrayCollection;
use Ice\FormBundle\Process\PlaceOrder\Step as Step;

use Ice\MinervaClientBundle\Entity\Booking;
use Ice\MinervaClientBundle\Entity\BookingItem;
use Ice\MinervaClientBundle\Entity\Category;
use Ice\MinervaClientBundle\Entity\RegistrationProgress;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Ice\VeritasClientBundle\Entity\Course;
use Ice\JanusClientBundle\Entity\User;
use Ice\MercuryClientBundle\Entity\CustomerInterface;

use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Ice\MinervaClientBundle\Entity\AcademicInformation;
use Ice\FormBundle\Process\PlaceOrder\Progress;

use Ice\FormBundle\Process\PlaceOrder\CalculatedPlanFactory;

use Symfony\Component\HttpFoundation\Response;

use Ice\MercuryClientBundle\Builder\ProposedSuborderFactory;

class PlaceOrder extends AbstractProcess
{
    /** @var Step\AbstractType */
    private $currentStep;

    /** @var Step\AbstractType[] */
    private $steps;

    /** @var string */
    private $customerId;

    /** @var \Ice\MercuryClientBundle\Entity\CustomerInterface */
    private $customer;

    /** @var AcademicInformation[] */
    private $academicInformations;

    /** @var Progress */
    private $progress;

    /** @var string */
    private $progressId;

    /** @var Response */
    private $ajaxResponse;

    /** @var CalculatedPlanFactory */
    private $planFactory;

    /** @var ProposedSuborderFactory */
    private $proposedSuborderFactory;

    /** @var User */
    private $user;

    /**
     * @param $index
     * @return Step\AbstractType
     * @throws \OutOfBoundsException
     */
    public function getStepByIndex($index)
    {
        $steps = $this->getSteps();
        if (isset($steps[$index])) {
            return $steps[$index];
        } else {
            throw new \OutOfBoundsException('No step with index ' . $index);
        }
    }

    /**
     * @param string $progressId
     * @return PlaceOrder
     */
    public function setProgressId($progressId)
    {
        $this->progressId = $progressId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProgressId()
    {
        if ($this->progressId === null) {
            $this->progressId = uniqid();
        }
        return $this->progressId;
    }

    /**
     * @return \Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep[]
     */
    public function getSteps()
    {
        if (null === $this->steps) {
            $this->steps = array(
                new Step\ChoosePlans\ChoosePlansType($this),
                new Step\Confirm\ConfirmType($this)
            );
        }
        return $this->steps;
    }


    /**
     * @param $reference
     * @return CourseRegistration\Step\AbstractRegistrationStep
     */
    public function getStepByReference($reference)
    {
        foreach ($this->getSteps() as $step) {
            if ($step->getReference() === $reference) {
                return $step;
            }
        }
    }

    /**
     * Processes an incoming submission (if any), advances the step if appropriate and prepares
     *
     * @param Request $request
     */
    public function processRequest(Request $request)
    {

        if ($request->getMethod() === 'POST') {
            if (null !== ($stepReference = $request->get('stepReference', null))) {
                $submittedStep = $this->getStepByReference($stepReference);
                $request->attributes->remove('stepReference');
                $this->setCurrentStep($submittedStep);

                if (null !== ($progressId = $request->get('progressId'))) {
                    $this->setProgressId($progressId);
                }

                if (null !== $request->get('process-navigation-back', null)) {
                    $this->setCurrentStepByIndex($submittedStep->getIndex() - 1);
                    $this->getCurrentStep()->prepare();
                    return;
                }

                $submittedStep->prepare();
                $submittedStep->processRequest($request);
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
                        return;
                    } else {
                        try {
                            $this->setCurrentStepByIndex($submittedStep->getIndex() + 1);
                        } catch (\OutOfBoundsException $e) {
                            $this->setCurrentStep($firstIncompleteStep);
                        }
                        $this->getCurrentStep()->prepare();
                    }
                } else {
                    $this->setCurrentStep($submittedStep);
                }
            }
        } else {
            $this->getCurrentStep()->prepare();
        }
    }

    /**
     * @param \Ice\FormBundle\Process\PlaceOrder\Step\AbstractType $currentStep
     * @return PlaceOrder
     */
    public function setCurrentStep($currentStep)
    {
        $this->currentStep = $currentStep;
        return $this;
    }

    /**
     * @param string $reference
     * @return PlaceOrder
     */
    public function setCurrentStepByReference($reference)
    {
        $this->currentStep = $this->getStepByReference($reference);
        return $this;
    }

    /**
     * @param int $index
     * @return PlaceOrder
     */
    public function setCurrentStepByIndex($index)
    {
        $this->currentStep = $this->getStepByIndex($index);
        return $this;
    }


    /**
     * @return \Ice\FormBundle\Process\PlaceOrder\Step\AbstractType
     */
    public function getCurrentStep()
    {
        if (null === $this->currentStep) {
            foreach ($this->getSteps() as $step) {
                if (!$step->isComplete() && $step->isAvailable()) {
                    $this->currentStep = $step;
                    break;
                }
            }
        }
        if (null === $this->currentStep) {
            foreach ($this->getSteps() as $step) {
                if ($step->isAvailable()) {
                    $this->currentStep = $step;
                    break;
                }
            }
            if (!$this->currentStep) {
                throw new \RuntimeException("No available order steps");
            }
        }
        return $this->currentStep;
    }

    /**
     * Returns HTML from <form> to </form> for the current step
     *
     * @param array $vars Vars to be passed to the template
     * @return string HTML output
     */
    public function renderStep(array $vars = array())
    {
        $currentStep = $this->getCurrentStep();
        if (!$currentStep->isPrepared()) $currentStep->prepare();
        return $currentStep->render($vars);
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        foreach ($this->getSteps() as $step) {
            if (!$step->isComplete()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $registrantId
     * @return PlaceOrder
     */
    public function setCustomerId($registrantId)
    {
        $this->customerId = $registrantId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param \Ice\MercuryClientBundle\Entity\CustomerInterface $customer
     * @return PlaceOrder
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @param User $user
     * @return PlaceOrder
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @throws \LogicException
     * @return \Ice\MercuryClientBundle\Entity\CustomerInterface
     */
    public function getCustomer()
    {
        if (!$this->customer) {
            throw new \LogicException("getCustomer called but Customer has not been set");
        }
        return $this->customer;
    }

    /**
     * @throws \LogicException
     * @return User
     */
    public function getUser()
    {
        if (!$this->user) {
            throw new \LogicException("getUser called but User has not been set");
        }
        return $this->user;
    }

    /**
     * @return AcademicInformation|\Ice\MinervaClientBundle\Entity\AcademicInformation[]
     */
    public function getAcademicInformations()
    {
        if ($this->academicInformations === null) {
            $this->academicInformations = $this->getMinervaClient()
                ->getAllAcademicInformationByIceId($this->getCustomerId());
        }
        return $this->academicInformations;
    }

    /**
     * @return Booking[]
     */
    public function getBookingsAvailableToOrder()
    {
        $bookings = array();
        foreach ($this->getAcademicInformations() as $ai) {
            if ($ai->isRegistrationComplete() &&
                ($ai->getPaymentStatusCode() === null) &&
                $ai->getCourse($this->getVeritasClient())->isInStock() &&
                !$ai->getCourse($this->getVeritasClient())->isCancelled() &&
                ($ai->getApplicationStatusCode() === null || $ai->isApplicationAccepted()) &&
                ($booking = $ai->getActiveBooking())
            ) {
                $booking->setAcademicInformation($ai);
                $bookings[] = $booking;
            }
        }
        return $bookings;
    }

    /**
     * @param \Ice\FormBundle\Process\PlaceOrder\Progress $progress
     * @return PlaceOrder
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Process\PlaceOrder\Progress
     */
    public function getProgress()
    {
        if (null === $this->progress) {
            $this->progress = $this->getSession()->get(
                'IceFormBundle:PlaceOrder:Progress:' . $this->getCustomerId() . ':' . $this->getProgressId()
            );
        }
        if (null === $this->progress) {
            $this->progress = new Progress();
        }
        return $this->progress;
    }

    /**
     * Persist the progress (to the session, in the current implementation)
     */
    public function saveProgress()
    {
        $this->getSession()->set('IceFormBundle:PlaceOrder:Progress:' . $this->getCustomerId() . ':' . $this->getProgressId(), $this->progress);
        $this->getSession()->save();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $ajaxResponse
     * @return PlaceOrder
     */
    public function setAjaxResponse($ajaxResponse)
    {
        $this->ajaxResponse = $ajaxResponse;
        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAjaxResponse()
    {
        return $this->ajaxResponse;
    }

    /**
     * @return CalculatedPlanFactory
     */
    public function getPlanFactory()
    {
        return $this->planFactory;
    }

    /**
     * @param CalculatedPlanFactory $paymentPlanCalculator
     * @return $this
     */
    public function setPlanFactory($paymentPlanCalculator)
    {
        $this->planFactory = $paymentPlanCalculator;
        return $this;
    }

    /**
     * @return ProposedSuborderFactory
     */
    public function getProposedSuborderFactory()
    {
        return $this->proposedSuborderFactory;
    }

    /**
     * @param ProposedSuborderFactory $proposedSuborderFactory
     * @return $this
     */
    public function setProposedSuborderFactory($proposedSuborderFactory)
    {
        $this->proposedSuborderFactory = $proposedSuborderFactory;
        return $this;
    }
}
