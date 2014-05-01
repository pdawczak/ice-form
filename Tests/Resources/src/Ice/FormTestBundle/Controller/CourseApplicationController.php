<?php

namespace Ice\FormTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CourseApplicationController extends Controller
{
    public function getGuestApplyAction($courseId)
    {
        return $this->getApplyAction(null, $courseId);
    }

    public function getResumeApplyAction($username, $courseId, $applicationId)
    {
        $courseId = intval($courseId);

        /** @var \Ice\FormBundle\Process\CourseApplicationProcess $process */
        $process = $this->get('ice.forms')->
                getCourseApplicationProcessFactory()->resumeCourseApplicationProcess(intval($applicationId), $courseId, $username);


        $process->setUrl($this->generateUrl('resume_apply',  ['username'=>$username, 'courseId'=>$courseId, 'applicationId'=>$applicationId]));

        $request = $this->getRequest();

        if (($stepNumber = $request->get('stepNumber', null)) !== null) {
            $process->setCurrentStepByIndex($stepNumber);
        }

        $process->processRequest($request);


        if ($request->isXmlHttpRequest()) {
            ob_end_clean();
            $response = $process->getStepAjaxResponse();
            $response->send();
            die;
        }


        if (null !== $process->getCourseApplication()->getApplicantId() && null === $username) {
            //User has logged in / been registered via the form
            return $this->redirect(
                $this->generateUrl('apply', ['username'=>$process->getCourseApplication()->getApplicantId(), 'courseId'=>$courseId])
            );
        }

        if (!$process->isComplete()) {
            //if (null !== $process->getRegistrantId() && ($stepIndex = JRequest::getVar('step', null)) !== null) {
            //    $process->setCurrentStepByIndex(intval($stepIndex) - 1);
            //}


        }

        return $this->render('IceFormTestBundle:Apply:view.html.twig', [
            'process' => $process->getView(),
            'course_id' => $courseId,
            'username' => $username,
            'applicationId' => $applicationId
        ]);
    }

    public function getApplyAction($username,$courseId)
    {
        $courseId = intval($courseId);
        if ($courseId && $username) {
            if ($ai = $this->getAcademicInformation($courseId, $username)) {
                //Look for applications in progress
                if ($ai->getCourseApplications()) {
                    foreach ($ai->getCourseApplications() as $courseApplication) {
                        if (!$courseApplication->getCompleted()) {
                            $applicationToResume = $courseApplication;
                            break;
                        }
                    }
                }
            }
        }



        /** @var \Ice\FormBundle\Process\CourseApplicationProcess $process */
        if (isset($applicationToResume)) {
            $process = $this->get('ice.forms')->
                getCourseApplicationProcessFactory()->resumeCourseApplicationProcess($applicationToResume->getId(), $courseId, $username);
        } else {
            $process = $this->get('ice.forms')->
                getCourseApplicationProcessFactory()->startCourseApplicationProcess($courseId, $username);
        }

        $process->setUrl($this->generateUrl('apply',  ['username'=>$username, 'courseId'=>$courseId]));

        $request = $this->getRequest();

        if (($stepNumber = $request->get('stepNumber', null)) !== null) {
            $process->setCurrentStepByIndex($stepNumber);
        }

        $process->processRequest($request);


        if ($request->isXmlHttpRequest()) {
            ob_end_clean();
            $response = $process->getStepAjaxResponse();
            $response->send();
            die;
        }


        if (null !== $process->getCourseApplication()->getApplicantId() && null === $username) {
            //User has logged in / been registered via the form
            return $this->redirect(
                $this->generateUrl('apply', ['username'=>$process->getCourseApplication()->getApplicantId(), 'courseId'=>$courseId])
            );
        }

        if (!$process->isComplete()) {
            //if (null !== $process->getRegistrantId() && ($stepIndex = JRequest::getVar('step', null)) !== null) {
            //    $process->setCurrentStepByIndex(intval($stepIndex) - 1);
            //}


        }

        return $this->render('IceFormTestBundle:Apply:view.html.twig', [
            'process' => $process->getView(),
            'course_id' => $courseId,
            'username' => $username
        ]);
    }

    /**
     * @param $courseId
     * @param $iceId
     * @return AcademicInformation|null
     */
    public function getAcademicInformation($courseId, $iceId)
    {
        try {
            //Query minerva
            return $this->get('minerva.client')->getAcademicInformation(
                $iceId,
                $courseId
            );

        } catch (\Ice\MinervaClientBundle\Exception\NotFoundException $e) {
            return null;
        }
    }
}
