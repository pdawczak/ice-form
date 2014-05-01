<?php

namespace Ice\FormBundle\Repository;

use Ice\FormBundle\Command\NewAccountCommand;
use Ice\FormBundle\Entity\Account;

interface AccountRepositoryInterface
{
    /**
     * @param string $iceId
     * @return Account
     */
    public function find($iceId);
}
