<?php

namespace Ice\FormBundle\Infrastructure\Janus;

use Ice\FormBundle\Command\NewAccountCommand;
use Ice\FormBundle\CommandHandler\AccountCommandHandlerInterface;
use Ice\JanusClientBundle\Exception\ValidationException;
use Ice\JanusClientBundle\Service\JanusClient;

class JanusClientAccountCommandHandler implements AccountCommandHandlerInterface
{
    /**
     * @var \Ice\JanusClientBundle\Service\JanusClient
     */
    private $client;

    /**
     * @var JanusClientValidationExceptionAdapter
     */
    private $validationExceptionAdapter;

    /**
     * @param JanusClient $client
     * @param JanusClientValidationExceptionAdapter $validationExceptionAdapter
     */
    public function __construct(
        JanusClient $client,
        JanusClientValidationExceptionAdapter $validationExceptionAdapter
    ) {
        $this->client = $client;
        $this->validationExceptionAdapter = $validationExceptionAdapter;
    }

    /**
     * @param NewAccountCommand $newAccountCommand
     * @throws \Ice\FormBundle\Exception\ValidationException
     */
    public function newAccount(NewAccountCommand $newAccountCommand)
    {
        $account = $newAccountCommand->getAccount();

        $parameters = [
            'title' => $account->getTitle(),
            'email' => $account->getEmailAddress(),
            'firstNames' => $account->getFirstNames(),
            'lastNames' => $account->getLastNames(),
            'plainPassword' => $newAccountCommand->getPlainPassword()
        ];

        if ($account->getDateOfBirth() instanceof \DateTime) {
            $parameters['dob'] = $account->getDateOfBirth()->format('Y-m-d');
        }

        try {
            $responseUser = $this->client->createUser($parameters);

            $account->setIceId($responseUser->getUsername());
        } catch (ValidationException $exception) {
            throw $this->validationExceptionAdapter->getNativeException(
                $exception,
                "Validation error while attempting to create a new account."
            );
        }
    }
}
