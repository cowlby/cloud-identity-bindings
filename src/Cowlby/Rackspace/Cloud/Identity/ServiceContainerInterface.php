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
	 * Returns a TokenManager instance which can be used to acquire tokens
	 * and service catalogs.
	 * 
     * @return \Cowlby\Rackspace\Cloud\Identity\TokenManager
	 */
	function getTokenManager();
	
	/**
	 * Returns a UserManager instance.
	 * 
     * @return \Cowlby\Rackspace\Cloud\Identity\UserManager
	 */
	function getUserManager();
}
