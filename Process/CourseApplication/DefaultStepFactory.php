<?php

namespace Ice\FormBundle\Process\CourseApplication;

use Ice\FormBundle\Process\CourseApplication\Exception\StepNotDefinedException;
use Ice\FormBundle\Process\CourseApplication\Feature\AccountRepositoryAwareInterface;
use Ice\FormBundle\Repository\AccountRepositoryInterface;
use Ice\FormBundle\Process\CourseApplicationProcess;

class DefaultStepFactory implements StepFactoryInterface, AccountRepositoryAwareInterface
{
    /**
     * @var VersionParser
     */
    private $versionParser;

    /**
     * @var StepDependencyInjector
     */
    private $stepDependencyInjector;

    /**
     * @param VersionParser $versionParser
     * @param StepDependencyInjector $dependencyInjector
     */
    public function __construct(
        VersionParser $versionParser,
        StepDependencyInjector $dependencyInjector
    )
    {
        $this->versionParser = $versionParser;
        $this->stepDependencyInjector = $dependencyInjector;
    }

    /**
     * @param $reference
     * @param $version
     * @throws Exception\StepNotDefinedException
     * @return mixed
     */
    public function getStep($reference, $version)
    {
        if($reference=='personalDetails') $reference = 'account';

        $className = 'Ice\\FormBundle\\Process\\CourseApplication\\Step\\' . ucwords($reference) . '\\V'.
            $this->versionParser->getMajorVersion($version).'\\' . ucwords($reference) . 'Step';

        if (class_exists($className)) {
            $step = new $className($reference, $version);
            $this->stepDependencyInjector->injectDependenciesInto($step);
            return $step;
        } else {
            throw new StepNotDefinedException(sprintf(
                    "Exception when instantiating a step for reference '%s' version '%s'. ".
                    "Class '%s' does not exist or cannot be loaded. ", $reference, $version, $className
            ));
        }
    }


    /**
     * @param AccountRepositoryInterface $accountRepository
     * @return $this
     */
    public function setAccountRepository($accountRepository)
    {
        $this->accountRepository = $accountRepository;
        return $this;
    }
}
