<?php

namespace Cowlby\Rackspace\Cloud\Identity\EntityManager;

interface AuthManagerInterface
{
    /**
     * Authenticates against the Cloud Identity API and retrieves the token
     * and service catalog data if successful.
     *
     * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Auth
     */
    function authenticate();
}
