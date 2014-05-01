<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

use Ice\FormBundle\CommandHandler\AccountCommandHandlerInterface;

interface AccountCommandHandlerAwareInterface
{
    /**
     * @param AccountCommandHandlerInterface $accountCommandHandler
     * @return $this
     */
    public function setAccountCommandHandler($accountCommandHandler);
}
