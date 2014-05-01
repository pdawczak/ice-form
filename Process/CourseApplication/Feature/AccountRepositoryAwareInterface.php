<?php

namespace Ice\FormBundle\Process\CourseApplication\Feature;

use Ice\FormBundle\Repository\AccountRepositoryInterface;

interface AccountRepositoryAwareInterface
{
    /**
     * @param AccountRepositoryInterface $accountRepository
     * @return $this
     */
    public function setAccountRepository($accountRepository);
}
