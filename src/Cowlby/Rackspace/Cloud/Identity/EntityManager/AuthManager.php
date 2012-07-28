<?php

namespace Cowlby\Rackspace\Cloud\Identity\EntityManager;

use Cowlby\Rackspace\Common\Http\ClientAdapterInterface;
use Cowlby\Rackspace\Common\Cache\CacheAdapterInterface;
use Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface;

/**
 * The AuthManager can authenticate against the Cloud Identity API and
 * provides access to a Token and a ServiceCatalog entity for use in
 * authentication and managing other cloud API endpoints.
 *
 * @author Jose Prado <cowlby@me.com>
 */
class AuthManager extends AbstractEntityManager implements AuthManagerInterface
{
    /**
     * @var CredentialsInterface
     */
    protected $credentials;

    /**
     * @var CacheAdapterInterface
     */
    protected $cache;

    /**
     * @var Entity\Auth
     */
    protected $auth;

    /**
     * {@inheritDoc}
     *
     * @param ClientAdapterInterface $client
     * @param CredentialsInterface $credentials
     * @param CacheAdapterInterface $cache
     */
    public function __construct(ClientAdapterInterface $client, CredentialsInterface $credentials, CacheAdapterInterface $cache)
    {
        parent::__construct($client);
        $this->setCredentials($credentials);
        $this->setCache($cache);
    }

    /**
     * Sets the credentials.
     *
     * @param CredentialsInterface $credentials
     * @return \Cowlby\Rackspace\Cloud\Identity\AuthManager
     */
    public function setCredentials(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * Sets the CacheAdapter to use for Token and ServiceCatalog caching.
     *
     * @param CacheAdapterInterface $cache
     * @return \Cowlby\Rackspace\Cloud\Identity\AuthManager
     */
    public function setCache(CacheAdapterInterface $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * Gets a unique cache id to use when storing the Auth entity.
     *
     * @return string
     */
    protected function getAuthCacheId()
    {
        return sprintf('[%s][%s]', md5($this->credentials->getPayload()), 'auth');
    }

    /**
     * {@inheritDoc}
     * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Auth
     */
    public function authenticate()
    {
        $auth = $this->cache->fetch($this->getAuthCacheId());

        if ($auth !== FALSE) {
            $auth = unserialize($auth);
        }

        if ($auth === FALSE || !$auth->isValid()) {
            $authClass = 'Cowlby\\Rackspace\\Cloud\\Identity\\Entity\\Auth';
            $auth = $this->client->post('auth', $authClass, $this->credentials->getPayload());
            $this->cache->save($this->getAuthCacheId(), serialize($auth));
        }

        return $auth;
    }
}
