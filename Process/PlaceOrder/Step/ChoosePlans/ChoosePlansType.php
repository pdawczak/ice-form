<?php
namespace Ice\FormBundle\Process\PlaceOrder\Step\ChoosePlans;

use Ice\FormBundle\Process\PlaceOrder\Step\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\MinervaClientBundle\Entity\Booking;
use Ice\VeritasClientBundle\Entity\PaymentPlan;

class ChoosePlansType extends AbstractType
{
    private $lines = array();

    public function getTemplate(){
        return 'ChoosePlans.html.twig';
    }

    public function getTitle(){
        return 'Choose payment plan'.(count($this->lines)>0?'s':'');
    }

    public function isAvailable(){
        return true;
    }

    public function buildForm(FormBuilderInterface $builder, array $options = array()){
        foreach($this->lines as $line){
            /** @var $booking Booking */
            $booking = $line['booking'];

            /** @var $plans PaymentPlan[] */
            $plans = $line['plans'];

            $plansPlain = array();
            foreach($plans as $k=>$v){
                $plansPlain[$k] = $v['description'];
            }

            $builder->add('plan-choice-booking-'.$booking->getId(), 'choice', array(
                'label'=>'Your choice of payment plan',
                'expanded'=>false,
                'multiple'=>false,
                'empty_value' => 'Choose a plan...',
                'choices'=>$plansPlain
            ));
        }

        parent::buildForm($builder, $options);
    }

    public function processRequest($request){
        $this->getForm()->bind($request);
        $data = $this->getForm()->getData();

        if($this->getForm()->isValid()){
            $planChoices = array();
            foreach($this->lines as $line){
                $booking = $line['booking'];
                $rawChoice = $data['plan-choice-booking-'.$booking->getId()];
                if($rawChoice!=='later'){
                    $planChoice = new PlanChoice();
                    $planChoice->setCode($rawChoice);
                    //TODO: Change this before version 2!
                    $planChoice->setVersion(1);
                    $planChoices[] = $planChoice;
                }
            }
            $this->getParentProcess()->getProgress()->setPlanChoices($planChoices);
            $this->getParentProcess()->saveProgress();
        }
    }

    public function render(array $vars = array())
    {
        $vars['lines'] = $this->lines;
        return parent::render($vars);
    }

    public function prepare(){
        if($this->isPrepared())
            return;

        $lines = array();
        $paymentPlanService = $this->getParentProcess()->getPaymentPlanService();

        $choices = $this->getParentProcess()->getProgress()->getPlanChoices();

        foreach($this->getParentProcess()->getBookingsAvailableToOrder() as $booking){
            $course = $this->getParentProcess()->getVeritasClient()->getCourse(
                $booking->getAcademicInformation()->getCourseId());
            $plans = array();
            foreach($course->getPaymentPlans() as $plan){
                $plans[$plan->getCode()] = array(
                    'code'=>$plan->getCode(),
                    'description'=>$paymentPlanService->getPaymentPlan($plan->getCode(), $plan->getVersion())->getShortDescription(),
                    'receivables'=>$paymentPlanService->getReceivables(
                        $plan->getCode(),
                        $plan->getVersion(),
                        $course->getStartDate(),
                        $booking->getBookingTotalPriceInPence()
                    )
                );
            }
            $lines[] = array(
                'booking'=>$booking,
                'course'=>$course,
                'plans'=>$plans
            );
        }
        $this->lines = $lines;
        $this->setPrepared();
    }

    public function isComplete(){
        return false;
    }

    public function getReference(){
        return 'choosePlans';
    }
}
