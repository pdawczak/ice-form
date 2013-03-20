<?php
namespace Ice\FormBundle\Process;

use Ice\FormBundle\Process\CourseRegistration\Step as Step;

use Ice\VeritasClientBundle\Entity\Course;

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

    private function createStepByReference($reference){
        $className = 'Ice\\FormBundle\\Process\\CourseRegistration\\Step\\'.ucwords($reference).'\\'.ucwords($reference);
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
            foreach($this->getCourse()->getCourseRegistrationRequirements() as $requirement){
                $this->steps[] = $this->createStepByReference($requirement->getCode());
            }
        }
        return $this->steps;
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
                $this->setCurrentStep($submittedStep);
                $submittedStep->processRequest($request);
                if($submittedStep->isComplete()){
                    $this->setCurrentStepByIndex($submittedStep->getIndex()+1);
                }
                else{
                    $this->setCurrentStep($submittedStep);
                }
            }
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
            $this->currentStep = $this->getSteps()[0];
        }
        return $this->currentStep;
    }

    /**
     * @return string
     */
    public function renderStep(){
        return $this->getCurrentStep()->render();
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
}