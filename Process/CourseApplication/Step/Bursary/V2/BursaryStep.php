<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Bursary\V2;

use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Feature\CourseRepositoryAwareInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;
use Ice\FormBundle\Entity\AvailableBursary;
use Ice\FormBundle\Repository\CourseRepositoryInterface;

class BursaryStep extends AbstractFormStep implements
    StepInterface,
    ExistingApplicationSupportInterface,
    CourseRepositoryAwareInterface
{
    private $data = null;

    /**
     * @var CourseRepositoryInterface|null
     */
    private $courseRepository = null;

    public function isAvailable()
    {
        return $this->applicationId !== null;
    }

    public function getTemplateVars()
    {
        $vars = parent::getTemplateVars();
        $vars['bursaries'] = [];

        $bursaries = $this->getFilteredBursaries();
        foreach ($bursaries as $code => $bursary) {
            $vars['bursaries'][$code] = [
                'value' => $bursary->getPrice() / (-100),
                'title' => $bursary->getTitle()
            ];
        }

        return $vars;
    }


    public function getFormOptions()
    {
        return [
            'step'=>$this,
            'bursaries'=>$this->getFilteredBursaries()
        ];
    }

    public function getFormType()
    {
        return new BursaryType();
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = new BursaryData();
        }
        return $this->data;
    }

    public function getJavaScriptTemplatePath()
    {
        return 'CourseApplication/Step/Bursary.js.twig';
    }

    /**
     * @param CourseRepositoryInterface $courseRepository
     * @return $this
     */
    public function setCourseRepository($courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @return \Ice\FormBundle\Entity\Course
     */
    private function getCourse()
    {
        static $course = null;
        if (!$course) {
            $course = $this->courseRepository->find($this->courseId);
        }
        return $course;
    }

    /**
     * Gets a filtered version of the course's available bursaries, in an array keyed by a convenient short code
     * for that bursary ('js', 'cup', or 'irh')
     *
     * @return array|AvailableBursary[]
     */
    private function getFilteredBursaries()
    {
        $course = $this->getCourse();
        $filteredBursaries = [];
        foreach ($course->getAvailableBursaries() as $availableBursary)
        {
            if (!$availableBursary->isAvailableInFrontEnd()) {
                continue;
            }

            if ($availableBursary->isCambridgeUniversityPress()) {
                $filteredBursaries['cup'] = $availableBursary;
            } else if ($availableBursary->isIvyRoseHood()) {
                $filteredBursaries['irh'] = $availableBursary;
            } else if ($availableBursary->isJamesStuart()) {
                $filteredBursaries['js'] = $availableBursary;
            }
        }

        return $filteredBursaries;
    }
}
