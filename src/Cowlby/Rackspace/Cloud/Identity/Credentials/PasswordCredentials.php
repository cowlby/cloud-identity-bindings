<?php

namespace Cowlby\Rackspace\Cloud\Identity\Credentials;

/**
 * Credentials implementation for username and password authentication.
 *
 * @author Jose Prado <cowlby@me.com>
 */
class PasswordCredentials implements CredentialsInterface
{
    /**
     * The Rackspace username.
     * @var string
     */
    protected $username;

    /**
     * The Rackspace password.
     * @var string
     */
    protected $password;

    /**
     * Constructor.
     *
     * @param string $username The username to authenticate.
     * @param string $password The password to authenticate with.
     */
    public function __construct($username, $password)
    {
        $this->setUsername($username);
        $this->setPassword($password);
    }

    /**
     * @see \Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface::getPayload()
     */
    public function getPayload()
    {
        return json_encode(array(
            'auth' => array(
                'passwordCredentials' => array(
                    'username' => $this->getUsername(),
                    'password' => $this->getPassword()
                )
            )
        ));
    }

    /**
     * Sets the username.
     *
     * @param string $username The username.
     * @return \Cowlby\Rackspace\Cloud\Identity\Credentials\PasswordCredentials
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Sets the password
     *
     * @param string $password The password.
     * @return \Cowlby\Rackspace\Cloud\Identity\Credentials\PasswordCredentials
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Returns the username.
     *
     * @return string The username.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the password.
     *
     * @return string The password.
     */
    public function getPassword()
    {
        return $this->password;
    }
}
