<?php

namespace Ice\FormBundle\Process\CourseApplication;

use Ice\FormBundle\Process\CourseApplication\Feature\AccountCommandHandlerAwareInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\AccountRepositoryAwareInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\FormFactoryAwareInterface;
use Ice\FormBundle\Repository\AccountRepositoryInterface;
use Ice\FormBundle\CommandHandler\AccountCommandHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class StepDependencyInjector
{
    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;

    /**
     * @var AccountCommandHandlerInterface
     */
    private $accountCommandHandler;

    /**
     * @var CourseApplicationRepositoryInterface
     */
    private $courseApplicationRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param \Ice\FormBundle\Repository\AccountRepositoryInterface $accountRepository
     * @return StepDependencyInjector
     */
    public function setAccountRepository($accountRepository)
    {
        $this->accountRepository = $accountRepository;
        return $this;
    }

    /**
     * @return \Ice\FormBundle\Repository\AccountRepositoryInterface
     */
    public function getAccountRepository()
    {
        return $this->accountRepository;
    }

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @return StepDependencyInjector
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;
        return $this;
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    public function setAccountCommandHandler($accountCommandHandler)
    {
        $this->accountCommandHandler = $accountCommandHandler;
    }

    /**
     * @param StepInterface $step
     */
    public function injectDependenciesInto(StepInterface $step)
    {
        if ($step instanceof AccountRepositoryAwareInterface) {
            $step->setAccountRepository($this->accountRepository);
        }

        if ($step instanceof FormFactoryAwareInterface) {
            $step->setFormFactory($this->formFactory);
        }

        if ($step instanceof AccountCommandHandlerAwareInterface) {
            $step->setAccountCommandHandler($this->accountCommandHandler);
        }
    }
}
