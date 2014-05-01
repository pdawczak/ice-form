<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Account\V1;

use Ice\FormBundle\Command\NewAccountCommand;
use Ice\FormBundle\CommandHandler\AccountCommandHandlerInterface;
use Ice\FormBundle\Entity\Account;
use Ice\FormBundle\Entity\CourseApplication;
use Ice\FormBundle\Exception\ValidationException;
use Ice\FormBundle\Form\Validation\ValidatableInterface;
use Ice\FormBundle\Process\CourseApplication\AbstractFormStep;
use Ice\FormBundle\Process\CourseApplication\Exception\StepNotDefinedException;
use Ice\FormBundle\Process\CourseApplication\Exception\StepNotReadyException;
use Ice\FormBundle\Process\CourseApplication\Feature\AccountCommandHandlerAwareInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\AnonymousSupportInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ExistingApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\FormFactoryAwareInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\KnownUserWithoutApplicationSupportInterface;
use Ice\FormBundle\Process\CourseApplication\Feature\ApplicantSourceInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;
use Ice\FormBundle\Repository\AccountRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Ice\FormBundle\Process\CourseApplication\Feature\AccountRepositoryAwareInterface;

class AccountStep extends AbstractFormStep implements
    StepInterface,
    AccountRepositoryAwareInterface,
    AccountCommandHandlerAwareInterface,
    AnonymousSupportInterface,
    KnownUserWithoutApplicationSupportInterface,
    ExistingApplicationSupportInterface,
    ApplicantSourceInterface
{
    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;

    private $iceId;

    private $data;

    /**
     * @var AccountCommandHandlerInterface
     */
    private $accountCommandHandler;

    public function isAvailable()
    {
        return $this->isInitialised();
    }

    public function isComplete()
    {
        return true;
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

    public function getFormType()
    {
        if (!$this->iceId) {
            return new RegistrationType();
        } else {
            return new ExistingAccountType();
        }
    }

    public function getFormOptions()
    {
        return ['step'=>$this];
    }

    public function getData()
    {
        if (!$this->isInitialised()) {
            throw new StepNotReadyException();
        }

        if (!$this->data) {
            $this->data = new AccountData();

            if ($this->iceId) {
                $account = $this->accountRepository->find($this->iceId);
                $this->data->setLastName($account->getLastNames());
            }
        }
        return $this->data;
    }

    /**
     * @param int $courseId
     * @return bool
     */
    public function initialiseAnonymously($courseId)
    {
        $this->setInitialised();
        $this->data = new AccountData();
        return true;
    }

    /**
     * @param int $courseId
     * @param $iceId
     * @param $applicationId
     * @return bool
     */
    public function initialiseWithApplication($courseId, $iceId, $applicationId)
    {
        $this->iceId = $iceId;
        $this->setInitialised();
        return true;
    }

    /**
     * @param int $courseId
     * @param string $iceId
     * @return bool
     */
    public function initialiseWithUserWithoutApplication($courseId, $iceId)
    {
        $this->iceId = $iceId;
        $this->setInitialised();
        return true;
    }

    public function onValidSubmission(ValidatableInterface $validator)
    {
        $data = $this->getData();
        if (!$this->iceId) {

            $account = (new Account())
                ->setLastNames($data->getLastName())
                ->setFirstNames($data->getFirstNames())
                ->setTitle($data->getTitle())
                ->setEmailAddress($data->getEmailAddress())
            ;

            try {
                $this->accountCommandHandler->newAccount(
                    new NewAccountCommand($account, $data->getPlainPassword())
                );

                $this->iceId = $account->getIceId();

            } catch (ValidationException $validationException) {
                $errors = $validationException->getErrorsAsArray();
                foreach ($errors as $field => $fieldErrors) {
                    $target = $this->getErrorTargetByFieldName($field);
                    foreach ($fieldErrors as $error)
                    {
                        if (stripos($error, 'email') !== false) {
                            $target = $this->getErrorTargetByFieldName('emailAddress');
                        }
                        $validator->addError($error, $target);
                    }
                }
            }
        }
    }

    /**
     * @return string|null
     */
    public function getAccountIceId()
    {
        return $this->iceId;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $fieldName
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getErrorTargetByFieldName($fieldName)
    {
        switch ($fieldName) {
            case null:
                $target = '.';
                break;
            case 'plainPassword':
                $target = 'plainPassword.first';
                break;
            default:
                $target = $fieldName;
                break;
        }
        return $target;
    }

    /**
     * @param AccountCommandHandlerInterface $accountCommandHandler
     * @return $this
     */
    public function setAccountCommandHandler($accountCommandHandler)
    {
        $this->accountCommandHandler = $accountCommandHandler;
        return $this;
    }
}
