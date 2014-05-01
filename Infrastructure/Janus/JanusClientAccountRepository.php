<?php

namespace Ice\FormBundle\Infrastructure\Janus;

use Ice\FormBundle\Command\NewAccountCommand;
use Ice\FormBundle\Repository\AccountRepositoryInterface;
use Ice\JanusClientBundle\Entity\User;
use Ice\JanusClientBundle\Exception\ValidationException;
use Ice\JanusClientBundle\Service\JanusClient;
use Ice\FormBundle\Entity\Account;

class JanusClientAccountRepository implements AccountRepositoryInterface
{
    /**
     * @var \Ice\JanusClientBundle\Service\JanusClient
     */
    private $client;

    /**
     * @var JanusClientUserToAccountAdapter
     */
    private $entityAdapter;

    /**
     * @var JanusClientValidationExceptionAdapter
     */
    private $validationExceptionAdapter;

    /**
     * @var Account[]
     */
    private $cachedAccounts = array();

    public function __construct(
        JanusClient $client,
        JanusClientUserToAccountAdapter $entityAdapter,
        JanusClientValidationExceptionAdapter $validationExceptionAdapter
    ) {
        $this->client = $client;
        $this->entityAdapter = $entityAdapter;
        $this->validationExceptionAdapter = $validationExceptionAdapter;
    }

    public function find($iceId)
    {
        if (!$this->isAccountInCache($iceId)) {
            $this->cacheAccount(
                $this->entityAdapter->getAccount($this->client->getUser($iceId))
            );
        }
        return $this->getCachedAccount($iceId);
    }

    public function reload(Account $account)
    {
        $this->cacheAccount(
            $this->entityAdapter->getAccount($this->client->getUser($account->getIceId()))
        );
        return $this->getCachedAccount($account->getIceId());
    }

    public function injectAccountByJanusClientUser(User $user)
    {
        $this->cacheAccount($this->entityAdapter->getAccount($user));
        return $this;
    }

    /**
     * Store an account in the local cache
     *
     * @param Account $account
     * @return $this
     */
    protected function cacheAccount(Account $account)
    {
        $this->cachedAccounts[$account->getIceId()] = $account;
        return $this;
    }

    protected function getCachedAccount($iceId)
    {
        return $this->cachedAccounts[$iceId];
    }

    protected function isAccountInCache($iceId)
    {
        return isset($this->cachedAccounts[$iceId]);
    }
}
