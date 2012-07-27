<?php

namespace Cowlby\Rackspace\Cloud\Identity\EntityManager;

use Guzzle\Http\ClientInterface;
use Cowlby\Rackspace\Cloud\Common\Cache\CacheAdapterInterface;
use Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface;
use Cowlby\Rackspace\Cloud\Identity\Entity;
use Guzzle\Http\Exception\BadResponseException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * The AuthManager can authenticate against the Cloud Identity API and
 * provides access to a Token and a ServiceCatalog entity for use in
 * authentication and managing other cloud API endpoints.
 *
 * @author Jose Prado <cowlby@me.com>
 */
class AuthManager extends AbstractEntityManager
{
    /**
     * @var \Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface
     */
    protected $credentials;

    /**
     * @var \Cowlby\Rackspace\Cloud\Common\Cache\CacheAdapterInterface
     */
    protected $cache;

    /**
     * @var \Cowlby\Rackspace\Cloud\Identity\Entity\Token
     */
    protected $token;

    /**
     * @var \Cowlby\Rackspace\Cloud\Identity\Entity\ServiceCatalog
     */
    protected $serviceCatalog;

    /**
     * {@inheritDoc}
     *
     * @param ClientInterface $client
     * @param SerializerInterface $serializer
     * @param CredentialsInterface $credentials
     * @param CacheAdapterInterface $cache
     */
    public function __construct(ClientInterface $client, SerializerInterface $serializer, CredentialsInterface $credentials, CacheAdapterInterface $cache)
    {
        parent::__construct($client, $serializer);
        $this->setCredentials($credentials);
        $this->setCache($cache);
    }

    /**
     * Sets the credentials.
     *
     * @param \Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface $credentials
     * @return \Cowlby\Rackspace\Cloud\Identity\TokenManager
     */
    public function setCredentials(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * Sets the CacheAdapter to use for Token and ServiceCatalog caching.
     *
     * @param \Cowlby\Rackspace\Cloud\Common\Cache\CacheAdapterInterface $cache
     * @return \Cowlby\Rackspace\Cloud\Identity\TokenManager
     */
    public function setCache(CacheAdapterInterface $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * Returns a valid Token to use for authentication.
     *
     * @return \Cowlby\Rackspace\Cloud\Identity\Entity\Token
     */
    public function getToken()
    {
        $token = $this->cache->fetch($this->getTokenCacheId());

        if ($token === FALSE) {

            $this->authenticate();
        } else {

            $this->token = unserialize($token);

            if (!$this->token->isValid()) {
                $this->authenticate();
            }
        }

        return $this->token;
    }

    /**
     * Returns a ServiceCatalog to use in finding service endpoints.
     *
     * @return \Cowlby\Rackspace\Cloud\Identity\Entity\ServiceCatalog
     */
    public function getServiceCatalog()
    {
        $serviceCatalog = $this->cache->fetch($this->getServiceCatalogCacheId());

        if ($serviceCatalog === FALSE) {
            $this->authenticate();
        } else {
            $this->serviceCatalog = unserialize($serviceCatalog);
        }

        return $this->serviceCatalog;
    }

    /**
     * Gets a unique cache id to use when storing the Token.
     *
     * @return string
     */
    protected function getTokenCacheId()
    {
        return sprintf('[%s][%s]', md5($this->credentials->getPayload()), 'token');
    }

    /**
     * Gets a unique cache id to use when caching the ServiceCatalog.
     *
     * @return string
     */
    protected function getServiceCatalogCacheId()
    {
        return sprintf('[%s][%s]', md5($this->credentials->getPayload()), 'serviceCatalog');
    }

    /**
     * Authenticates against the Cloud Identity API and retrieves the token
     * and service catalog data if successful.
     *
     * @return \Cowlby\Rackspace\Cloud\Identity\TokenManager
     */
    protected function authenticate()
    {
        $request = $this->client->post('auth', NULL, $this->credentials->getPayload());
        $response = $this->client->send($request);

        $data = $this->serializer->decode($response->getBody(), 'json');

        $tokenClass = 'Cowlby\\Rackspace\\Cloud\\Identity\\Entity\\Token';
        $this->token = $this->serializer->denormalize($data['auth']['token'], $tokenClass);

        $catalogClass = 'Cowlby\\Rackspace\\Cloud\\Identity\\Entity\\ServiceCatalog';
        $this->serviceCatalog = $this->serializer->denormalize($data['auth']['serviceCatalog'], $catalogClass);

        $this->cache->save($this->getTokenCacheId(), serialize($this->token));
        $this->cache->save($this->getServiceCatalogCacheId(), serialize($this->serviceCatalog));

        return $this;
    }
}
