<?php

namespace Ice\FormBundle\Infrastructure\Minerva;

use Ice\FormBundle\Entity\CourseApplication;
use Ice\FormBundle\Infrastructure\Minerva\MinervaClientCourseApplicationAdapter;
use Ice\FormBundle\Repository\AccountRepositoryInterface;
use Ice\FormBundle\Repository\CourseApplicationRepositoryInterface;

use Ice\MinervaClientBundle\Service\MinervaClient;
use Ice\MinervaClientBundle\Entity\CourseApplication as MinervaClientCourseApplication;
use Ice\FormBundle\Entity\Account;

class MinervaClientCourseApplicationRepository implements CourseApplicationRepositoryInterface
{
    /**
     * @var MinervaClient
     */
    private $client;

    /**
     * @var MinervaClientCourseApplicationAdapter
     */
    private $adapter;

    /**
     * @var CourseApplication[]
     */
    private $cachedApplications = array();

    public function __construct(
        MinervaClient $client,
        MinervaClientCourseApplicationAdapter $adapter
    ) {
        $this->client = $client;
        $this->adapter = $adapter;
    }

    /**
     * @param MinervaClientCourseApplication $mcApplication
     * @param $courseId
     * @param $applicantId
     * @return $this
     */
    public function injectApplicationByMinervaClientApplication(MinervaClientCourseApplication $mcApplication, $courseId, $applicantId)
    {
        $this->cacheCourseApplication($this->adapter->getCourseApplication($mcApplication, $courseId, $applicantId));
        return $this;
    }

    /**
     * Store an account in the local cache
     *
     * @param CourseApplication $courseApplication
     * @return $this
     */
    protected function cacheCourseApplication(CourseApplication $courseApplication)
    {
        $this->cachedApplications[$courseApplication->getId()] = $courseApplication;
        return $this;
    }

    protected function getCachedCourseApplication($iceId)
    {
        return $this->cachedApplications[$iceId];
    }

    protected function isCourseApplicationInCache($iceId)
    {
        return isset($this->cachedApplications[$iceId]);
    }

    /**
     * @param CourseApplication $courseApplication
     * @return mixed
     */
    public function persistAndFlush(CourseApplication $courseApplication)
    {
        $mcApplication = $this->adapter->getMinervaClientCourseApplication($courseApplication);

        if ($id = $courseApplication->getId()) {
            foreach ($courseApplication->getSteps() as $step) {
                if ($step->isDirty()) {
                    if ($matchingStep = $mcApplication->getCourseApplicationStepByName($step->getName())) {

                        if (!$matchingStep->getCompleted() && $step->isComplete()) {
                            $matchingStep->setCompleted(new \DateTime());
                        }

                        $this->client->updateCourseApplicationStep(
                            $id,
                            $matchingStep
                        );
                    }
                }
            }
        } else {
            $this->client->beginCourseApplication(
                $courseApplication->getApplicantId(),
                $courseApplication->getCourseId(),
                $mcApplication
            );
        }
    }

    /**
     * Return a CourseApplication entity, or null if none exists.
     *
     * @param $applicationId
     * @param $courseId
     * @param $applicantId
     * @return mixed
     */
    public function find($applicationId, $courseId, $applicantId)
    {
        if (!$this->isCourseApplicationInCache($applicationId)) {
            $this->cacheCourseApplication(
                $this->adapter->getCourseApplication($this->client->getCourseApplication($applicationId), $courseId, $applicantId)
            );
        }
        return $this->getCachedCourseApplication($applicationId);
    }

    /**
     * Return true if the application is sufficiently complete to be persisted.
     *
     * @param CourseApplication $courseApplication
     * @return bool
     */
    public function canPersist(CourseApplication $courseApplication)
    {
        return $courseApplication->getApplicantId() && $courseApplication->getCourseId();
    }
}
