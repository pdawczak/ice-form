<?php
namespace Ice\FormBundle\Process;

use Ice\FormBundle\Process\CourseRegistration\Step as Step;

use Ice\MinervaClientBundle\Entity\Booking;
use Ice\MinervaClientBundle\Entity\RegistrationProgress;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Ice\VeritasClientBundle\Entity\Course;
use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\AcademicInformation;

use Symfony\Component\HttpFoundation\Request;

class CourseRegistration extends AbstractProcess
{
    /** @var Step\AbstractRegistrationStep */
    private $currentStep;

    /** @var Step\AbstractRegistrationStep[] */
    private $steps;

    /** @var string $url */
    private $url;

    /** @var int $courseId */
    private $courseId;

    /** @var Course */
    private $course;

    /** @var string */
    private $registrantId;

    /** @var User */
    private $registrant;

    /** @var \Ice\MinervaClientBundle\Entity\Booking */
    private $booking;

    /** @var AcademicInformation */
    private $academicInformation;

    /** @var \Ice\MinervaClientBundle\Entity\RegistrationProgress */
    private $progress;

    /**
     * @param string $reference
     * @return Step\AbstractRegistrationStep
     */
    public function getStepByReference($reference){
        foreach($this->getSteps() as $step){
            if($step->getReference() === $reference){
                return $step;
            }
        }
        return null;
    }

    /**
     * @param int $index
     * @return Step\AbstractRegistrationStep
     */
    public function getStepByIndex($index){
        return $this->getSteps()[$index];
    }

    /**
     * @param $reference
     * @return Step\AbstractRegistrationStep
     */
    private function createStepByReference($reference){
        $className = 'Ice\\FormBundle\\Process\\CourseRegistration\\Step\\'.ucwords($reference).'\\'.ucwords($reference).'Type';
        $reflectionClass = new \ReflectionClass($className);
        return $reflectionClass->newInstance($this, $reference);
    }

    public function loadExisting(){

    }

    /**
     * @return \Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep[]
     */
    public function getSteps(){
        if(null === $this->steps){
            $this->steps = array();
            if($this->registrantId){
                $progress = $this->getProgress(true);
                foreach($progress->getStepProgresses() as $step){
                    $newStep = $this->createStepByReference($step->getStepName());
                    $newStep->setStepProgress($step);
                    $this->steps[] = $newStep;
                }
            }
            else{
                foreach($this->getCourse()->getCourseRegistrationRequirements() as $requirement){
                    $this->steps[] = $this->createStepByReference($requirement->getCode());
                }
            }
        }
        return $this->steps;
    }

    /**
     * @param \Ice\MinervaClientBundle\Entity\RegistrationProgress $progress
     * @return CourseRegistration
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Entity\RegistrationProgress
     */
    public function getProgress($create = false)
    {
        if(!$this->progress){
            $booking = $this->getBooking(true);
            if($progress = $booking->getRegistrationProgress()){
                return $progress;
            }
            else if($create){
                $this->getMinervaClient()->setRegistration($this->getRegistrantId(), $this->getCourseId());
                $progress = $this->buildRegistrationProgress();
                $this->steps = array();
                foreach($progress->getStepProgresses() as $step){
                    $this->getMinervaClient()->setRegistrationStep($this->getRegistrantId(), $this->getCourseId(), array(
                        'stepName'=>$step->getStepName(),
                        'order'=>$step->getOrder(),
                        'description'=>$step->getDescription()
                    ));
                    $newStep = $this->createStepByReference($step->getStepName());
                    $newStep->setStepProgress($step);
                    $this->steps[] = $newStep;
                }
                $this->progress = $progress;
            }
        }
        return $this->progress;
    }


    /**
     * Processes an incoming submission (if any), advances the step if appropriate and prepares
     *
     * @param Request $request
     */
    public function processRequest(Request $request){

        if($request->getMethod()==='POST'){
            if(null !== ($stepReference = $request->get('stepReference', null))){
                $submittedStep = $this->getStepByReference($stepReference);
                $request->attributes->remove('stepReference');
                $this->setCurrentStep($submittedStep);
                $submittedStep->prepare();
                $submittedStep->processRequest($request);
                if($submittedStep->isComplete()){
                    $this->setCurrentStepByIndex($submittedStep->getIndex()+1);
                }
                else{
                    $this->setCurrentStep($submittedStep);
                }
            }
        }
        else{
            $this->getCurrentStep()->prepare();
        }
    }

    /**
     * @param \Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep $currentStep
     * @return CourseRegistration
     */
    public function setCurrentStep($currentStep)
    {
        $this->currentStep = $currentStep;
        return $this;
    }

    /**
     * @param string $reference
     * @return CourseRegistration
     */
    public function setCurrentStepByReference($reference)
    {
        $this->currentStep = $this->getStepByReference($reference);
        return $this;
    }

    /**
     * @param int $index
     * @return CourseRegistration
     */
    public function setCurrentStepByIndex($index)
    {
        $this->currentStep = $this->getStepByIndex($index);
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep
     */
    public function getCurrentStep()
    {
        if(null === $this->currentStep){
            foreach($this->getSteps() as $step){
                if(!$step->isComplete()){
                    $this->currentStep = $step;
                    break;
                }
            }
        }
        if(null === $this->currentStep){
            $steps = $this->getSteps();
            $this->currentStep = $steps[0];
        }
        return $this->currentStep;
    }

    /**
     * @return string
     */
    public function renderStep(){
        $currentStep = $this->getCurrentStep();
        if(!$currentStep->isPrepared()) $currentStep->prepare();
        return $currentStep->render();
    }

    /**
     * @return bool
     */
    public function isComplete(){
        return false;
    }

    /**
     * @param string $url
     * @return CourseRegistration
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param int $courseId
     * @return CourseRegistration
     */
    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @param \Ice\VeritasClientBundle\Entity\Course $course
     * @return CourseRegistration
     */
    public function setCourse(Course $course)
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return \Ice\VeritasClientBundle\Entity\Course
     */
    public function getCourse()
    {
        if(!$this->course && $this->getCourseId()){
            $this->setCourse($this->getVeritasClient()->getCourse($this->getCourseId()));
        }
        return $this->course;
    }

    /**
     * @param string $registrantId
     * @return CourseRegistration
     */
    public function setRegistrantId($registrantId)
    {
        $this->registrantId = $registrantId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegistrantId()
    {
        return $this->registrantId;
    }

    /**
     * @param \Ice\JanusClientBundle\Entity\User $registrant
     * @return CourseRegistration
     */
    public function setRegistrant($registrant)
    {
        $this->registrant = $registrant;
        return $this;
    }

    /**
     * @return \Ice\JanusClientBundle\Entity\User
     */
    public function getRegistrant()
    {
        if(!$this->registrant && $this->getRegistrantId()){
            $this->setRegistrant($this->getJanusClient()->getUser($this->getRegistrantId()));
        }
        return $this->registrant;
    }

    /**
     * @param \Ice\MinervaClientBundle\Entity\AcademicInformation $academicInformation
     * @return CourseRegistration
     */
    public function setAcademicInformation($academicInformation)
    {
        $this->academicInformation = $academicInformation;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Entity\AcademicInformation
     */
    public function getAcademicInformation($fetch = false)
    {
        if(!$this->academicInformation){
            if(!$this->getCourseId() || !$this->getRegistrantId()){
                throw new \RuntimeException("getAcademicInformation called with insufficient information");
            }
            if($fetch){
                try{
                    $this->academicInformation = $this->getMinervaClient()
                        ->getAcademicInformation($this->getRegistrantId(), $this->getCourseId());
                }
                catch(\Minerva\NotFoundException $e){
                    $this->academicInformation = null;
                }
            }
        }
        return $this->academicInformation;
    }

    /**
     * @param bool $create Create the booking if it does not exist
     * @return \Ice\MinervaClientBundle\Entity\Booking
     */
    public function getBooking($create = false)
    {
        if(!$this->booking && $create){
            $this->booking = $this->beginBooking();
        }
        return $this->booking;
    }

    private function beginBooking()
    {
        if($this->getBooking()){ //Booking already begun
            return $this->getBooking();
        }

        else{
            try{
                $ai = $this->getMinervaClient()->getAcademicInformation(
                    $this->getRegistrantId(),
                    $this->getCourseId());
            }
            catch(NotFoundException $e404){
                $this->getMinervaClient()->createAcademicInformation(
                    $this->getRegistrantId(),
                    $this->getCourseId(),
                    array());
                $ai = $this->getMinervaClient()->getAcademicInformation(
                    $this->getRegistrantId(),
                    $this->getCourseId());
            }

            if($booking = $ai->getActiveBooking()){
                return $booking;
            }

            $booking = new Booking();
            $booking->setAcademicInformation($ai);
            $booking->setBookedBy($ai->getIceId());
            $this->getMinervaClient()->createBooking(
                $ai->getIceId(),
                $ai->getCourseId(),
                array(
                    'bookedBy'=>$booking->getBookedBy()
                )
            );

            return $booking;
        }
    }

    /**
     * Builds and returns a new RegistrationProgress suitable for this course
     *
     * @return RegistrationProgress
     */
    private function buildRegistrationProgress(){
        $progress = new RegistrationProgress();
        $requirements = $this->getCourse()->getCourseRegistrationRequirements();
        foreach($requirements as $i=>$req){
            $stepProgress = new StepProgress();
            $stepHandler = $this->createStepByReference($req->getCode());
            $stepProgress->setStepName($stepHandler->getReference());
            $stepProgress->setDescription($stepHandler->getTitle());
            $stepProgress->setOrder($i+1);
            $stepHandler->setStepProgress($stepProgress);
            $progress->addStepProgress($stepProgress);
        }
        return $progress;
    }
}