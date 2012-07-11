<?php

namespace Cowlby\Rackspace\Cloud\Identity;

use Cowlby\Rackspace\Cloud\Common\HydratorInterface;
use Cowlby\Rackspace\Cloud\Common\Cache\CacheAdapterInterface;
use Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface;
use Cowlby\Rackspace\Cloud\Identity\Entity;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\BadResponseException;

/**
 * The TokenManager can authenticate against the Cloud Identity API and
 * provides access to a Token and a ServiceCatalog entity for use in
 * authentication and managing other cloud API endpoints.
 * 
 * @author Jose Prado <cowlby@me.com>
 */
class TokenManager
{
	/**
	 * @var \Guzzle\Service\Client
	 */
    protected $client;
    
    /**
     * @var \Cowlby\Rackspace\Cloud\Identity\Credentials\CredentialsInterface
     */
    protected $credentials;

    /**
     * @var \Cowlby\Rackspace\Cloud\Common\HydratorInterface
     */
    protected $hydrator;

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

    public function __construct(CredentialsInterface $credentials, GuzzleClient $client, HydratorInterface $hydrator, CacheAdapterInterface $cache)
    {
        $this->setCredentials($credentials);
        $this->setClient($client);
        $this->setHydrator($hydrator);
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
     * Sets the HTTP client.
     * 
     * @param \Guzzle\Service\Client $client
     * @return \Cowlby\Rackspace\Cloud\Identity\TokenManager
     */
    public function setClient(GuzzleClient $client)
    {
        $this->client = $client;
        return $this;
    }
    
    /**
     * Sets the Hydrator for entity hydration.
     * 
     * @param \Cowlby\Rackspace\Cloud\Common\HydratorInterface $hydrator
     * @return \Cowlby\Rackspace\Cloud\Identity\TokenManager
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
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
     * Gets a unique cache id to use when storing the Token.
     * 
     * @return string
     */
    protected function getTokenCacheId()
    {
    	return sprintf('[%s][%s]', md5($this->credentials->getPayload()), 'token');
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
        $request = $this->client->post('tokens', NULL, $this->credentials->getPayload());
        $response = $request->send();

        $json = json_decode($response->getBody(), TRUE);

        $this->token = new Entity\Token();
        $this->hydrator->hydrateEntity($this->token, $json['access']['token']);

        $this->serviceCatalog = new Entity\ServiceCatalog();
        foreach ($json['access']['serviceCatalog'] as $jsonService) {

            $service = new Entity\Service();
            $this->hydrator->hydrateEntity($service, $jsonService);
            $this->serviceCatalog->addService($service);

            foreach ($jsonService['endpoints'] as $jsonEndpoint) {

                $endpoint = new Entity\Endpoint();
                $this->hydrator->hydrateEntity($endpoint, $jsonEndpoint);
                $service->addEndpoint($endpoint);
            }
        }

        $this->cache->save($this->getTokenCacheId(), serialize($this->token));
        $this->cache->save($this->getServiceCatalogCacheId(), serialize($this->serviceCatalog));

        return $this;
    }
}
