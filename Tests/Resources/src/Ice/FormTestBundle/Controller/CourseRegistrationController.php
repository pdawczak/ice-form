<?php

namespace Ice\FormTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CourseRegistrationController extends Controller
{
    public function getRegisterAction($username,$courseId)
    {
        $courseId = intval($courseId);

            $process = $this->get('ice.forms')->
                beginCourseRegistrationProcess($courseId, $username);

        $process->setUrl($this->generateUrl('register',  ['username'=>$username, 'courseId'=>$courseId]));

        if ($username) {
            $process->setRegistrantId($username);
        }

        $request = $this->getRequest();

        if (($stepNumber = $request->get('stepNumber', null)) !== null) {
            $process->setCurrentStepByIndex($stepNumber-1);
        }

        $process->processRequest($request);


        if ($request->isXmlHttpRequest()) {
            ob_end_clean();
            $response = $process->getStepAjaxResponse();
            $response->send();
            die;
        }


        /**if (null !== $process->getCourseApplication()->getApplicantId() && null === $username) {
            //User has logged in / been registered via the form
            return $this->redirect(
                $this->generateUrl('apply', ['username'=>$process->getCourseApplication()->getApplicantId(), 'courseId'=>$courseId])
            );
        }

        if (null !== $process->getRegistrantId() && null === $iceId) {
            //User has logged in / been registered via the form
            $accountModel = CoursesModelAccount::withIceId($process->getRegistrantId());
            $accountModel->setLoggedIn();
            $this->setRedirect(JRoute::_('index.php?option=com_courses&view=enrol&cid=' . $courseId .
            '&who=self', false, 1));
            return;
        }

        if (!$process->isComplete()) {
            if (null !== $process->getRegistrantId() && ($stepIndex = JRequest::getVar('step', null)) !== null) {
                $process->setCurrentStepByIndex(intval($stepIndex) - 1);
            }


        }
**/
        return $this->render('IceFormTestBundle:Register:view.html.twig', [
            'process' => $process,
            'course_id' => $courseId,
            'username' => $username
        ]);
    }
}
