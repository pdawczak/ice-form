<?php

namespace Ice\FormBundle\CommandHandler;

use Ice\FormBundle\Command\NewAccountCommand;

interface AccountCommandHandlerInterface
{
    public function newAccount(NewAccountCommand $newAccountCommand);
}
