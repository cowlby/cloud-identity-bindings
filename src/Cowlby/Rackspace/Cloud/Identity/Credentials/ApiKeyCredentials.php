<?php

namespace Cowlby\Rackspace\Cloud\Identity\Credentials;

/**
 * Credentials implementation for username and API key authentication.
 *
 * @author Jose Prado <cowlby@me.com>
 */
class ApiKeyCredentials implements CredentialsInterface
{
    /**
     * The Rackspace username.
     * @var string
     */
    protected $username;

    /**
     * The Rackspace API key.
     * @var string
     */
    protected $apiKey;

    /**
     * Constructor.
     *
     * @param string $username The username to authenticate.
     * @param string $apiKey The API key to authenticate with.
     */
    public function __construct($username, $apiKey)
    {
        $this->setUsername($username);
        $this->setApiKey($apiKey);
    }

    /**
     * @see \Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface::getPayload()
     */
    public function getPayload()
    {
        return json_encode(array(
            'credentials' => array(
                'username' => $this->getUsername(),
                'key' => $this->getApiKey()
            )
        ));
    }

    /**
     * Sets the username.
     *
     * @param string $username The username.
     * @return \Cowlby\Rackspace\Cloud\Identity\Credentials\ApiKeyCredentials
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Sets the API key.
     *
     * @param unknown_type $apiKey The API key.
     * @return \Cowlby\Rackspace\Cloud\Identity\Credentials\ApiKeyCredentials
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Returns the username.
     * @return string The username.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the API key.
     * @return string The API key.
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
