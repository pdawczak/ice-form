<?php
namespace Ice\FormBundle\Process;

use Doctrine\Common\Collections\ArrayCollection;
use Ice\FormBundle\Process\CourseRegistration\Step as Step;

use Ice\MinervaClientBundle\Entity\Booking;
use Ice\MinervaClientBundle\Entity\BookingItem;
use Ice\MinervaClientBundle\Entity\Category;
use Ice\MinervaClientBundle\Entity\RegistrationProgress;
use Ice\MinervaClientBundle\Entity\StepProgress;
use Ice\MinervaClientBundle\Exception\NotFoundException;
use Ice\VeritasClientBundle\Entity\Course;
use Ice\JanusClientBundle\Entity\User;
use Ice\MinervaClientBundle\Entity\AcademicInformation;

use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseRegistration extends AbstractProcess
{
    /** @var Step\AbstractRegistrationStep */
    private $currentStep;

    /** @var Step\AbstractRegistrationStep[] */
    private $steps;

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

    /** @var Response */
    private $ajaxResponse = null;

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
     * @param $index
     * @return Step\AbstractRegistrationStep
     * @throws \OutOfBoundsException
     */
    public function getStepByIndex($index){
        $steps = $this->getSteps();
        if(isset($steps[$index])){
            return $steps[$index];
        }
        else{
            throw new \OutOfBoundsException('No step with index '.$index);
        }
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
            $booking = $this->getBooking($create);
            if($booking && $progress = $booking->getRegistrationProgress()){
                $this->progress = $progress;
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
        if ($request->isXmlHttpRequest()) {
            $this->processAjaxRequest($request);
        }
        else if($request->getMethod()==='POST'){
            if(null !== ($stepReference = $request->get('stepReference', null))){
                $submittedStep = $this->getStepByReference($stepReference);
                $request->attributes->remove('stepReference');
                $this->setCurrentStep($submittedStep);
                $submittedStep->prepare();
                $submittedStep->processRequest($request);
                if($submittedStep->isComplete()){
                    $firstIncompleteStep = null;
                    $wholeProcessComplete = true;
                    foreach($this->getSteps() as $firstIncompleteStep){
                        if(!$firstIncompleteStep->isComplete()){
                            $wholeProcessComplete = false;
                            break;
                        }
                    }
                    if($wholeProcessComplete){
                        if($this->getProgress()->getCompleted()===null){
                            $this->getProgress()->setCompleted(new \DateTime());
                        }
                        return;
                    }
                    else{
                        try{
                            $this->setCurrentStepByIndex($submittedStep->getIndex()+1);
                        }
                        catch(\OutOfBoundsException $e){
                            $this->setCurrentStep($firstIncompleteStep);
                        }
                    }
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

    protected function processAjaxRequest(Request $request)
    {
        if(null !== ($stepReference = $request->get('stepReference', null))){
            $submittedStep = $this->createStepByReference($stepReference);
            $submittedStep->processAjaxRequest($request);
        }
        else {
            return new Response('AJAX response not supported when no step reference is supplied.', 412);
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
                if(!$step->isComplete() && $step->isAvailable()){
                    $this->currentStep = $step;
                    break;
                }
            }
        }
        if(null === $this->currentStep){
            foreach($this->getSteps() as $step){
                if($step->isAvailable()){
                    $this->currentStep = $step;
                    break;
                }
            }
            if(!$this->currentStep){
                throw new \RuntimeException("No available registration steps");
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
    public function renderStepHtml(array $vars = array()){
        $currentStep = $this->getCurrentStep();
        if(!$currentStep->isPrepared()) $currentStep->prepare();
        return $currentStep->renderHtml($vars);
    }

    /**
     * Returns any JavaScript that is required for the current step.
     *
     * @param array $vars
     *
     * @return string
     */
    public function renderStepJavaScript(array $vars = array()){
        $currentStep = $this->getCurrentStep();
        if(!$currentStep->isPrepared()) $currentStep->prepare();
        return $currentStep->renderJavaScript($vars);
    }

    /**
     * Returns a partial representation of the current step.
     *
     * Not all steps will require have an Ajax Response.
     *
     * @return Response
     */
    public function getStepAjaxResponse()
    {
        return $this->getAjaxResponse();
    }

    /**
     * Whether the current step has an Ajax response.
     *
     * @return bool
     */
    public function stepSupportsAjaxResponse()
    {
        return true;
        return $this->getCurrentStep()->supportsAjaxResponse();
    }

    /**
     * @return bool
     */
    public function isComplete(){
        if(!$this->getProgress()){
            return false;
        }
        return $this->getProgress()->getCompleted()!==null;
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
                catch(NotFoundException $e){
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

            $booking = $this->buildBooking($ai);

            foreach($booking->getBookingItems() as $bookingItem){
                $bookingItems[] = array(
                    'description'=>$bookingItem->getDescription(),
                    'price'=>$bookingItem->getPrice(),
                    'code'=>$bookingItem->getCode(),
                    'category'=>$bookingItem->getCategory()->getCode(),
                );
            }

            $this->getMinervaClient()->createBooking(
                $ai->getIceId(),
                $ai->getCourseId(),
                array(
                    'bookedBy'=>$booking->getBookedBy(),
                    'bookingItems'=>$bookingItems
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

    /**
     * Builds and returns a new Booking suitable for this course and person
     * @param AcademicInformation $ai
     * @return Booking
     */
    private function buildBooking(AcademicInformation $ai){
        $booking = new Booking();
        $booking->setAcademicInformation($ai);
        $booking->setBookedBy($ai->getIceId());

        $course = $this->getCourse();
        $bookingItemsArray = array();
        $courseFees = new BookingItem();
        $category = new Category();
        $category
            ->setCode(5)
            ->setDescription('Tuition')
        ;
        $courseFees
            ->setDescription('Course fees')
            ->setPrice($course->getTuitionFee())
            ->setCode('TUIT')
            ->setCategory($category)
        ;
        $bookingItemsArray[] = $courseFees;
        $booking->setBookingItems(new ArrayCollection(
            $bookingItemsArray
        ));
        return $booking;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $ajaxResponse
     * @return CourseRegistration
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
}