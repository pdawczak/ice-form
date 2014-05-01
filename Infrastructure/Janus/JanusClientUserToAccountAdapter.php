<?php

namespace Ice\FormBundle\Infrastructure\Janus;

use Ice\FormBundle\Entity\Account;
use Ice\JanusClientBundle\Entity\User;

/**
 * Converts JanusClient User entities to Account entities.
 *
 * Class JanusClientToUserAccountAdapter
 * @package Ice\FormBundle\Infrastructure\JanusClient
 */
class JanusClientUserToAccountAdapter
{
    /**
     * @param User $user
     * @return Account
     */
    public function getAccount(User $user)
    {
        $account = (new Account())
            ->setIceId($user->getUsername())
            ->setLastNames($user->getLastNames())
            ->setFirstNames($user->getFirstNames())
            ->setTitle($user->getTitle())
            ->setEmailAddress($user->getEmail())
        ;
        return $account;
    }
}
