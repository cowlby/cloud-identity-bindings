<?php

namespace Cowlby\Rackspace\Cloud\Identity\EntityManager;

interface AuthManagerInterface
{
    function getToken();

    function getServiceCatalog();
}
