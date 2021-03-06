<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\ChoosePlans;

use Ice\FormBundle\Infrastructure\Minerva\MinervaClientBookingAdapter;
use Ice\FormBundle\Process\PlaceOrder\Step\AbstractType;
use Ice\MinervaClientBundle\Response\FormError;
use Ice\PaymentPlan\PlanDefinition;
use Ice\PaymentPlan\PlanParameters;
use Money\Money;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\VeritasClientBundle\Entity\PaymentPlan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Request;

class ChoosePlansType extends AbstractType
{
    private $lines = array();

    public function getTemplate()
    {
        return 'ChoosePlans.html.twig';
    }

    public function getTitle()
    {
        return 'Choose payment plan' . (count($this->lines) > 0 ? 's' : '');
    }

    public function isAvailable()
    {
        return true;
    }

    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        foreach ($this->lines as $line) {
            /** @var $booking Booking */
            $booking = $line['booking'];

            /** @var $plans PaymentPlan[] */
            $plans = $line['plans'];

            $plansPlain = array();
            foreach ($plans as $k => $v) {
                $plansPlain[$k] = $v['description'];
            }


            if (count($this->lines) > 1) {
                $plansPlain['later'] = 'Pay for this course later';
            }

            $data = null;
            if ($existingChoice = $this->getParentProcess()->getProgress()->getPlanChoiceByBookingId($booking->getId())) {
                $data = $existingChoice->getHash();
            }


            $builder->add('plan-choice-booking-' . $booking->getId(), 'choice', array(
                'label' => 'Your choice of payment plan',
                'expanded' => false,
                'multiple' => false,
                'data' => $data,
                'empty_value' => 'Choose a plan...',
                'choices' => $plansPlain,
                'required' => true,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Please choose a payment plan.'
                    ))
                )
            ));
        }

        parent::buildForm($builder, $options);
    }

    public function processRequest(Request $request)
    {
        $this->getForm()->bind($request);
        $data = $this->getForm()->getData();

        if ($this->getForm()->isValid()) {
            $planChoices = array();
            foreach ($this->lines as $line) {
                /** @var $booking Booking */
                $booking = $line['booking'];
                $rawChoice = $data['plan-choice-booking-' . $booking->getId()];
                if ($rawChoice !== 'later') {
                    $planChoice = PlanChoice::withBookingIdAndHash($booking->getId(), $rawChoice);
                    $planChoices[] = $planChoice;
                }
            }

            $this->getStepProgress()->setComplete();
            $this->getParentProcess()->getProgress()->setPlanChoices($planChoices);
            $this->getParentProcess()->saveProgress();
        }
    }

    public function render(array $vars = array())
    {
        $vars['lines'] = $this->lines;

        return parent::render($vars);
    }

    public function prepare()
    {
        if ($this->isPrepared())
            return;

        $lines = array();
        $paymentPlanFactory = $this->getParentProcess()->getPlanFactory();
        $bookingAdapter = new MinervaClientBookingAdapter();

        foreach ($this->getParentProcess()->getBookingsAvailableToOrder() as $booking) {
            $course = $booking->getAcademicInformation()->getCourse();
            $plans = array();
            $i=1;
            foreach ($course->getAvailablePaymentPlans() as $availablePlan) {

                $calculatedPlan = $paymentPlanFactory->calculatePlan(
                    $availablePlan->getDefinition(),
                    $bookingAdapter->getBooking($booking)
                );

                $hash = PlanChoice::getDefinitionHash($availablePlan->getDefinition());

                $plans[$hash] = array(
                    'code' => $availablePlan->getDefinition()->getName(),
                    'description' => $calculatedPlan->getShortDescription(),
                    'planned_payments' => $calculatedPlan->getPlannedPayments()
                );
                $i++;
            }
            $lines[] = array(
                'booking' => $booking,
                'course' => $course,
                'plans' => $plans
            );
        }
        $this->lines = $lines;
        $this->setPrepared();
    }

    public function isComplete()
    {
        return $this->getStepProgress()->isComplete();
    }

    public function getReference()
    {
        return 'choosePlans';
    }
}
