<?php

namespace Cowlby\Rackspace\Cloud\Identity;

/**
 * Generic interface
 *
 * @author Jose Prado <cowlby@me.com>
 */
interface ServiceContainerInterface
{
    /**
     * Returns a AuthManagerInterface instance which can be used to acquire
     * tokens and service catalogs.
     *
     * @return \Cowlby\Rackspace\Cloud\Identity\AuthManagerInterface
     */
    function getAuthManager();
}
