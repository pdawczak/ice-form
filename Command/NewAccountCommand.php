<?php

namespace Ice\FormBundle\Command;

use Ice\FormBundle\Entity\Account;

class NewAccountCommand
{
    /**
     * @var \Ice\FormBundle\Entity\Account
     */
    private $account;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @param Account $account
     * @param $plainPassword
     */
    public function __construct(
        Account $account,
        $plainPassword
    ) {
        $this->account = $account;
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return \Ice\FormBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
}
