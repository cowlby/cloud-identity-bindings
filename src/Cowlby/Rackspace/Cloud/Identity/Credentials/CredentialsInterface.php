<?php

namespace Cowlby\Rackspace\Cloud\Identity\Credentials;

/**
 * Credentials interface to convert a set of credentials into a payload body.
 * 
 * @author Jose Prado <cowlby@me.com>
 */
interface CredentialsInterface
{
	/**
	 * Returns the api-ready payload string to use for authentication.
	 * 
	 * @return string The payload string.
	 */
	public function getPayload();
}
